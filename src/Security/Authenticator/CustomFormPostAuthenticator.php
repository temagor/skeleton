<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use App\Exception\ValidateException;
use App\Repository\CredentialRepository;
use App\Repository\UserRepository;
use App\Security\Authentication\CustomFormPostAuthenticationFailureHandler;
use App\Security\Authentication\CustomFormPostAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\ParameterBagUtils;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class CustomFormPostAuthenticator extends AbstractLoginFormAuthenticator
{
    private Request $request;
    private ?string $authenticationType;
    private ?string $authenticationValue;
    private bool $needPasswordProtection = false;
    private ?string $password;

    public function __construct(
        private HttpUtils $httpUtils,
        private UserProviderInterface $userProvider,
        private CustomFormPostAuthenticationSuccessHandler $successHandler,
        private CustomFormPostAuthenticationFailureHandler $failureHandler,
        private UserRepository $userRepository,
        private CredentialRepository $credentialRepository,
        private array $options = []
    ) {
        $this->options = array_merge([
            'username_parameter' => '_username',
            'password_parameter' => '_password',
            'check_path' => '/user/actions/api/sign-in',
            'post_only' => true,
            'form_only' => false,
            'enable_csrf' => false,
            'csrf_parameter' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ], $options);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->httpUtils->generateUri($request, $this->options['login_path']);
    }

    public function supports(Request $request): bool
    {
        return ($this->options['post_only'] ? $request->isMethod('POST') : true)
            && $this->httpUtils->checkRequestPath($request, $this->options['check_path'])
            && ($this->options['form_only'] ? 'form' === $request->getContentType() : true);
    }

    public function authenticate(Request $request): Passport
    {
        $this->request = $request;
        $this->fillAuthenticationParams();

        return $this->authenticateByType();
    }

    private function fillAuthenticationParams(): void
    {
        $credentialList = $this->request->request->all()['credentialList'];
        foreach ($credentialList as $type => $value) {
            if ($type !== 'password' && !empty($value)) {
                $this->authenticationType = $type;
                $this->authenticationValue = $value;
                continue;
            }
            if ($type === 'password') {
                $this->password = $value;
            }
        }
    }

    public function authenticateByType(): Passport
    {
        return match ($this->authenticationType) {
            'login' => $this->authenticateByLogin(),
            'email' => $this->authenticateByEmail(),
            'phoneNumber' => $this->authenticateByPhoneNumber(),
        };
    }

    private function authenticateByLogin(): Passport
    {
        $badge = new UserBadge($this->authenticationType, function () {
            return $this->userRepository->findOneBy(['login' => $this->authenticationValue]);
        });

        /** @var User $user */
        $user = $badge->getUser();

        if ($user->isProtected() && !$this->password) {
            throw new ValidateException(new ConstraintViolationList([new ConstraintViolation('show password input', null, [], false, 'password', 'password')]));
        }

        return match ($user->isProtected()) {
            true => new Passport(
                $badge,
                new PasswordCredentials($this->password),
                [new RememberMeBadge()]
            ),
            false => new SelfValidatingPassport($badge)
        };
    }

    private function authenticateByEmail(): Passport
    {
        $badge = new UserBadge($this->authenticationType, function () {
            return $this->credentialRepository->findOneBy(['value' => $this->authenticationValue])?->getUser();
        });

        /** @var User $user */
        $user = $badge->getUser();

        if ($user->isProtected() && !$this->password) {
            throw new ValidateException(new ConstraintViolationList([new ConstraintViolation('show password input', null, [], false, 'password', 'password')]));
        }

        return match ($user->isProtected()) {
            true => new Passport(
                $badge,
                new PasswordCredentials($this->password),
                [new RememberMeBadge()]
            ),
            false => new SelfValidatingPassport($badge)
        };
    }

    private function authenticateByPhoneNumber(): Passport
    {
        $badge = new UserBadge($this->authenticationType, function () {
            return $this->credentialRepository->findOneBy(['value' => $this->authenticationValue])?->getUser();
        });

        /** @var User $user */
        $user = $badge->getUser();

        if ($user->isProtected() && !$this->password) {
            throw new ValidateException(new ConstraintViolationList([new ConstraintViolation('show password input', null, [], false, 'password', 'password')]));
        }

        return match ($user->isProtected()) {
            true => new Passport(
                $badge,
                new PasswordCredentials($this->password),
                [new RememberMeBadge()]
            ),
            false => new SelfValidatingPassport($badge)
        };
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        return new UsernamePasswordToken($passport->getUser(), $firewallName, $passport->getUser()->getRoles());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return $this->successHandler->onAuthenticationSuccess($request, $token);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->failureHandler->onAuthenticationFailure($request, $exception);
    }

    public function setHttpKernel(HttpKernelInterface $httpKernel): void
    {
        $this->httpKernel = $httpKernel;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        if (!$this->options['use_forward']) {
            return parent::start($request, $authException);
        }

        $subRequest = $this->httpUtils->createRequest($request, $this->options['login_path']);
        $response = $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        if (200 === $response->getStatusCode()) {
            $response->setStatusCode(401);
        }

        return $response;
    }
}
