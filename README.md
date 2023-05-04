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
VPN_USER=$vpnUsername
VPN_PASS=$vpnPassword
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
DNS=172.20.0.16
DNS=172.20.1.23
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
VPN_USER=$vpnUsername
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

Additionally, when connecting to the servers via SSH, you might have to run

```
systemctl restart systemd-networkd.service
systemctl restart systemd-resolved.service
```

again after each system start.

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

### Promoting users
To promote users from standard editor role to ChiefEditor (Hoerbiger.Shared:ChiefEditor) run the following command (adjust username to your needs):

```sh
docker-compose exec -T php php /var/www/html/hoerbiger_deployer/current/flow editor:promote 'HOERBIGER-COM\uext_abc'     
```

## Installation

Install dependencies via:
```sh
make install
```

Build frontend via 
```sh
make build
```

## Running the site locally

You can start a development server via:

```sh
make up
```

## Clone project data

To clone project data, you must be connected to the hoerbiger VPN

Also, your VPN user must be added to .ddev/config/ssh-config.txt as follows:
```sh
Host hoerbiger
    HostName 172.20.245.101
    User $VPN_USER
```
The VPN user must also be added to the MagicWand configuration so in the Configuration/Settings.Sitegeist.MagicWand.yaml:

```sh
Sitegeist:
    MagicWand:
        clonePresets:
            develop:
                user: $VPN_USER
        
            staging:
                user: $VPN_USER
        
            master:
                user: $VPN_USER
```

You can now clone the project data directly with make. This command shows up the list and ask for the preset:

```
make clone
```

or type the preset directly:

```sh
make clone-develop

make clone-staging

make clone-master
```

## Versioning

This project follows the trunk-based approach for continuous integration.

All branches are directly derived from master and named after the corresponding ticket's number, regardless of whether they're features, bugfixes or anything else.

Ticket branches are independently merged to develop, then staging, then master. All branches except master can at all times be deleted and recreated from master.

### Branching Model

    12345                        +- Commit -+----+----+
                                /            \    \    \
    develop              +------------- Merge -+------------------------> develop
                        /      /                     \    \
    staging      +------------------------- Merge -+-----------------> staging
                /     /      /                              \
    master ----+-----+------+------------------------ Merge -+----------> master


## Deployment

> To deploy the project, you must be connected to the hoerbiger VPN

Deployment can currently only be done manually and locally.
To do this, connect to the hoerbiger VPN and run the respective commands:

```sh
make deploy-develop

make deploy-staging

make deploy-master
```
