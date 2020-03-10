# Repman - PHP Repository Manager

Repman is a PHP repository manager. Main features:
 - work as proxy for packagist.org (speed up your local builds)
 - host your private packages
 - allow to create individual access tokens
 - import private packages from GitHub, GitLab and Bitbucket with one click

## Requirements

 - PHP >= 7.4.1
 - `var` dir must be writeable
 - any web server

## Installation

```
git clone git@github.com:buddy-works/repman.git
cd repman
composer install
```

## Workers

To process messages asynchronously you must run worker:
```
bin/console messenger:consume async
```
Read more: https://symfony.com/doc/current/messenger.html#deploying-to-production

## Usage

Navigate your browser to instance address, you will see home page with usage instructions.

## Local proxy

On dev env you may want to enable proxy to allow to create subdomains and tests composer organizations:

```
composer proxy-setup
```

This will create `repman.wip` domain. Then you can add other domains with:

```
symfony proxy:domain:attach your-organization.repman
```

### CLI commands

 - `bin/console repman:metadata:clear-cache` - clear packages metadata cache (json files)


## Roadmap

 - [ ] support for docker (to allow to create repman instance with docker)
 - [ ] manual webook installation

## Integration

Callbacks:
 - `/auth/{provider}/check`
 - `/register/{provider}/check`
 - `/user/token/{provider}/check`

### GitHub

Scopes:
 - registration: `user:email`
 - repositories: `read:org`, `repo`

### GitLab

Scopes:
 - registration: `read_user`
 - repositories: `api`

### Bitbucket

Scopes:
 - registration: `email`
 - repositories: `repository`, `webhook`
