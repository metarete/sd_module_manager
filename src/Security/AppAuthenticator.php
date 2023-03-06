<?php

namespace App\Security;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;


class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private EntityManagerInterface $em;
    private $client;
    private $params;
    private $security;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $em, HttpClientInterface $client, ParameterBagInterface $params, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->em = $em;
        $this->client = $client;
        $this->params = $params;
        $this->security = $security;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        // recupero l'utente in base al fatto che al login abbia inserito username (SD Manager) o email (locale)
        $userRepo = $this->em->getRepository(User::class);
        $validator = Validation::createValidator();
        $violations = $validator->validate($email, [
            new Email(),
        ]);
        $isSDManagerUser = false;
        if (0 !== count($violations)) {
            // si tratta di uno username
            $user = $userRepo->findOneBy(['username' => $email]);
            $isSDManagerUser = true;
        } else {
            // si tratta di una mail
            $user = $userRepo->findOneBy(['email' => $email]);
        }

        if ($user) {
            if ($isSDManagerUser) {
                // si tratta di un utente importato da SD Manager
                if ($this->sdManagerLoginCheck($email, $password)) {
                    // l'utente ha un account valido su SD Manager
                    $currentEmail = $user->getEmail();
                    return (new SelfValidatingPassport(new UserBadge($currentEmail)));
                } else {
                    // l'utente NON ha un account valido su SD Manager
                    return (new Passport(new UserBadge($email), new PasswordCredentials('')));
                }
            } else {
                // si tratta di un utente locale di Symfony
                return new Passport(
                    new UserBadge($email),
                    new PasswordCredentials($request->request->get('password', '')),
                    [
                        new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                    ]
                );
            }
        } else {
            // non si tratta di un utente registrato...
            return (new Passport(new UserBadge($email), new PasswordCredentials('')));
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        //if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
          //  return new RedirectResponse($targetPath);
        //}
        $user = $this->security->getUser();
        $role = $user->getRoles();
        
        if($role[0]=='ROLE_ADMIN')
            return new RedirectResponse($this->urlGenerator->generate('app_scheda_pai_index'));
        else
            return new RedirectResponse($this->urlGenerator->generate('app_scadenzario_index'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    private function sdManagerLoginCheck($username, $password) {
        // controllo se si tratta di un utente attivo su SDManager
        $today = new DateTime('now');
        $todayDate = $today->format('Y-m-d');
        // il token di controllo è dato dall'hash MD5 della concatenzaione di 
        // data odierna (YYYY-MM-DD) e username passato nella form
        $token = md5($todayDate.$username);
        // recupero l'URL da chiamare
        $url = $this->params->get('app.ws_sdmanager_api_url');
        // metto tutto nei parametri della POST
        $payload = [
            'username' => $username,
            'password' => $password,
            'token' => $token
        ];
        // faccio la richiesta di verifica
        $response = $this->client->request(
            'POST',
            $url,
            [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Accept' => 'application/json'
                ],
                'body' => json_encode($payload)
            ]
        );
        // ritorno esito
        if ($response->getStatusCode() == 200) {
            return (true);
        } else {
            return (false);
        }
    }
}
