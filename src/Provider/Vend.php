<?php

namespace SumaerJolly\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Vend extends AbstractProvider
{
  const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'id';

  /**
   * @var string This will be prepended to the base uri.
   * @link https://docs.vendhq.com/docs/authorization
   */
  protected $subdomain;


  /**
   * Constructs an OAuth 2.0 service provider.
   *
   * @param array $options An array of options to set on this provider.
   *     Options include `clientId`, `clientSecret`, `redirectUri`, and `state`.
   *     Individual providers may introduce more options, as needed.
   * @param array $collaborators An array of collaborators that may be used to
   *     override this provider's default behavior. Collaborators include
   *     `grantFactory`, `requestFactory`, `httpClient`, and `randomFactory`.
   *     Individual providers may introduce more collaborators, as needed.
   */
  public function __construct(array $options = [], array $collaborators = [])
  {
    parent::__construct($options, $collaborators);

    if (empty($this->subdomain)) {
      throw new IdentityProviderException(
        'No subdomain has been configured for this Vend provider; it has to have a subdomain.',
        0,
        []
      );
    }
  }

  public function getBaseAuthorizationUrl()
  {
    return 'https://secure.vendhq.com/connect';
  }

  public function getBaseAccessTokenUrl(array $params)
  {
    return 'https://' . $this->subdomain . '.vendhq.com/api/1.0/token';
  }

  public function getResourceOwnerDetailsUrl(AccessToken $token)
  {
    return $this->getSubdomainName();
  }


  public function getDefaultScopes()
  {
    return [];
  }

  public function checkResponse(ResponseInterface $response, $data)
  {
    if (!empty($data['errors'])) {
      throw new IdentityProviderException($data['errors'], 0, $data);
    }

    return $data;
  }

  protected function createResourceOwner(array $response, AccessToken $token)
  {
    return null;
  }

  /**
   * Get the subdomain name
   * @return string
   */
  protected function getSubdomainName()
  {
    return $this->subdomain;
  }
}
