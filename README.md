# Hoerbiger Neos Distribution

## What's different

### VPN

The customer's infrastructure is protected from outside access.
Thus for server-side connections (deployment, cloning) as well as client-side connections to develop, staging and the master backend,
an active VPN connection is required.

#### VPN Setup

The following steps are to be executed once on project setup.

**Installing the VPN client**
```sh
sudo apt-get install openfortivpn
```

> Execute the following commands as sudo -i

```sh
export VPN_USER=$vpnUsername
export VPN_PASS=$vpnPassword
```

**Creating the ip-up config file**
```sh
cat > /etc/ppp/ip-up.d/hoerbiger <<'EOF'
#!/bin/bash

IPS='172.20.0.0/16'

INTERFACE=$1

if [ "$INTERFACE" != "hoerbiger" ]; then
exit 0
fi

for IP in $IPS; do
ip route add $IP dev "$INTERFACE"
done
EOF
```

```sh
chmod +x /etc/ppp/ip-up.d/hoerbiger
```

**Setting DNS**
```
cat >> /etc/systemd/network/10-hoerbiger.network <<EOF
[Match]
Name=hoerbiger

[Network]
DNS=172.20.0.14
Domains=hoerbiger.com
EOF
```

```
systemctl restart systemd-networkd.service
systemctl restart systemd-resolved.service
```

**Storing the VPN connection data**
```sh
cat >> /etc/openfortivpn/hoerbiger <<EOF
host = df3.hoerbiger.com
username = $VPN_USER
password = $VPN_PASS
set-dns = 0
pppd-use-peerdns = 0
set-routes = 0
pppd-ifname = hoerbiger
EOF
```

**Adjusting the SSH config**

> Execute the following commands as regular user

```sh
export VPN_USER=$vpnUsername
```

```sh
cat >> ~/.ssh/config <<EOF
Host hoerbiger
    HostName 172.20.245.101
    User $VPN_USER
EOF
```

#### VPN Connection

You can now connect to the VPN via

```sh
sudo openfortivpn -c /etc/openfortivpn/hoerbiger
```

Keep the tab open for keeping the connection alive.

### Deployment

Deployment must be done manually as the deployment server cannot connect to the target servers.

**develop**
```sh
bin/dep deploy develop
```
**staging**
```sh
bin/dep deploy staging
```
**master**
```sh
bin/dep deploy master
```

### Running remote Flow commands
On the target server, the application is run in a docker container, so Flow commands must be executed via docker-compose:

**develop**
```sh
cd /srv/dockerdata/www-dev

docker-compose exec -T php php /var/www/html/hoerbiger_deployer/current/flow
```

**staging**
```sh
cd /srv/dockerdata/www-stage

docker-compose exec -T php php /var/www/html/hoerbiger_deployer/current/flow
```

**master**
```sh
cd /srv/dockerdata/www-prod

docker-compose exec -T php php /var/www/html/hoerbiger_deployer/current/flow
```


> The follwing part is not yet up to date
## Installation

Create a project based on the sitegeist base distribution

```sh
composer create-project sitegeist/neos-base-distribution customer-folder
```

Migrate database and add Admin user
```sh
ddev flow doctrine:migrate
ddev flow create:user --roles Administrator admin admin Admin User
```

Copy the included `Vendor.Site` package into the project namespace#

```sh
ddev flow package:adopt Vendor.Site Customer.Site
```

Require the Project package and remove the dependencies to `Vendor.Site` and `Sitegeist.Chantalle`

```sh
ddev composer require customer/site
ddev composer remove vendor/site
ddev composer remove sitegeist/chantalle
```

Initialize the project git repository
```sh
git init
```

Install dependencies via:

```sh
make install
```

Finally, perform a site import:

```sh
ddev flow site:import --package-key Customer.Site
```

## Running the site locally

You can start a development server via:

```sh
make up
```

## Clone project data

You can clone your projectdata directly with make. This command shows up the list and ask for the preset.

```
make clone
```

or type the preset directly:

```sh
make clone
```

## Versioning

<!-- @TODO: Versioning -->

## Deployment

<!-- @TODO: Deployment -->
