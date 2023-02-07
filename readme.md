# Luckyseven JWT-AUTH Bundle
This bundle provides access token and refresh token functionalities.

Lexik is used for standard JWT authentication.<br>
Gesdinet is used for refresh token

We configured Lexik to use the user email to attempt login and to store the user id in the JWT.<br>
Gesdinet can't utilize the same login provider as Lexik because it fetches user via Id instead Email (because we configured Gesdinet to indentify users via Id).<br>
The solution is to create an ad hoc provider for Gesdinet.

## Update composer.json and register the repositories
```
{
    ...
    "repositories": [
        {"type": "git", "url":  "https://github.com/giacomoto/jwt-auth-bundle.git"}
    ],
    ...
    "extra": {
        "symfony": {
            ...
            "endpoint": [
                "https://api.github.com/repos/giacomoto/jwt-auth-recipes/contents/index.json",
                "flex://defaults"
            ]
        }
    }
}
```

## Install
```
composer require symfony/orm-pack
composer require doctrine/annotations
composer require luckyseven/jwt-auth:dev-main

composer recipes:install luckyseven/jwt-auth --force -v
```

# Setup Lexik Bundle

## Generate Certs:
```bash
php bin/console lexik:jwt:generate-keypair
```
## Edit config/packages/lexik_jwt_authentication.yaml
```bash
lexik_jwt_authentication:
    ...
    user_identity_field: '%env(USER_IDENTITY_FIELD)%'
    token_ttl: '%env(int:ACCESS_TOKEN_TTL)%'
```

# Setup Gesdinet Bundle

## Replace config/packages/gesdinet_jwt_refresh_token.yaml
```bash
gesdinet_jwt_refresh_token:
    refresh_token_class: Luckyseven\Bundle\LuckysevenJwtAuthBundle\Entity\RefreshToken
    ttl: '%env(int:REFRESH_TOKEN_TTL)%'
    token_parameter_name: refreshToken
    user_identity_field: '%env(USER_IDENTITY_FIELD)%'
    single_use: true
```

# Setup Security Bundle
## Edit config/packages/security.yaml
```
security:
    
    ...
    
    providers:
        # Lexik Jwt
        app_user_login_provider:
            entity:
                class: App\Entity\User
                property: email
        # Gesdinet Refresh Token
        app_user_jwt_provider:
            entity:
                class: App\Entity\User
                property: id
        
        app_users_login:
            chain:
                providers: [ 'app_user_login_provider' ]
        app_users_jwt_provider:
            chain:
                providers: [ 'app_user_jwt_provider' ]

    firewall:
        
        ...
        
        login:
            pattern: ^/api/v1/auth/login
            stateless: true
            provider: app_users_login
            json_login:
                check_path: /api/v1/auth/login
                username_path: email
                password_path: password
                success_handler: lexik_jwt_authentication.handler.authentication_success
                failure_handler: lexik_jwt_authentication.handler.authentication_failure
        api:
            pattern: ^
            stateless: true
            provider: app_users_jwt_provider
            jwt: ~
            refresh_jwt:
                check_path: api_refresh_token
                provider: app_users_jwt_provider
            entry_point: jwt
        main:
            lazy: true
            provider: app_users_jwt_provider
            
            
    access_control:
        ...
        
        - { path: ^/api/v1/auth/login,   roles: PUBLIC_ACCESS }
        - { path: ^/api/v1/auth/refresh, roles: PUBLIC_ACCESS }
```

# Finish
- Remove the unnecessary file routes/gesdinet_jwt_refresh_token.yaml 
- Remove the unnecessary entity RefreshToken.php
- Make migration for RefreshToken entity  ```php bin/console make:migration```
