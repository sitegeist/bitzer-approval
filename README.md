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

**Setting up hosts**
```sh
cat >> /etc/hosts <<'EOF'
172.20.245.102 www-dev.hoerbiger.com
172.20.245.103 www-stage.hoerbiger.com
172.20.245.104 www-prod.hoerbiger.com
EOF
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
