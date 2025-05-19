# Hoerbiger Neos Distribution

## What's different

### VPN

The customer's infrastructure is protected from outside access.
Thus for server-side connections (deployment, cloning) as well as client-side connections to develop, staging and the main backend,
an active VPN connection is required.

#### VPN Setup on Linux

The following steps are to be executed once on project setup.

**Install OpenConnect network manager**
```sh
sudo apt-get install network-manager-openconnect-gnome
```

**Setup new VPN**

Protocol: Fortinet SSL VPN

VPN tab:

| Option | Wert |
| --- | --- |
| Gateway | df6.hoerbiger.com |
| Token Mode | HOTP - manually entered |

IPv4 Settings tab:

| Option | Wert |
| --- | --- |
| Method | Automatic (VPN) addresses only |
| DNS servers | 172.20.244.52, 172.20.244.40 |
| Search domains | hoerbiger.com |

**Activate systemd-networkd**
```sh
sudo systemctl enable --now systemd-networkd
```

**Installing the VPN client**
```sh
sudo apt-get install openfortivpn
```

> Execute the following commands as sudo -i

```sh
VPN_USER=$vpnUsername
VPN_PASS=$vpnPassword
```

**Store the VPN connection data**
```sh
touch /etc/openfortivpn/hoerbiger.conf
chmod 0600 /etc/openfortivpn/hoerbiger.conf
cat <<EOF | sudo tee /etc/openfortivpn/hoerbiger.conf >/dev/null
host = df3.hoerbiger.com
username = ${VPN_USER}
password = ${VPN_PASS}
set-dns = 0
pppd-use-peerdns = 1
set-routes = 0
pppd-ifname = hoerbiger
EOF
```

**Create the ip-up config file**

```sh
cat <<'EOF' | sudo tee /etc/ppp/ip-up.d/hoerbiger >/dev/null
#!/bin/bash

[ "${PPP_IFACE}" != "hoerbiger" ] && exit 0

function kill_ppp {
    kill -HUP "${PPPD_PID}" 
    exit 1
}

[ -n "${DNS1}" ] && DNS+="DNS=${DNS1}" 
[ -n "${DNS2}" ] && [ "${DNS1}" != "${DNS2}" ] && DNS+=$'\n'"DNS=${DNS2}" 

cat > /etc/systemd/network/10-hoerbiger.network <<EOT
[Match]
Name=hoerbiger

[Address]
Address=${PPP_LOCAL}/16

[Network]
Domains=hoerbiger.com
DHCPv6PrefixDelegation=no
LinkLocalAddressing=no
${DNS}
EOT

networkctl reload

#rm /etc/systemd/network/10-hoerbiger.network || true

count=0
while true; do
    ((++count < 10)) || kill_ppp
    if
        ping -c1 www-dev.hoerbiger.com >/dev/null 2>&1 &&
        ping -c1 www-stage.hoerbiger.com >/dev/null 2>&1 &&
        ping -c1 www-prod.hoerbiger.com >/dev/null 2>&1
    then
        break
    fi
    sleep 1
done
true
EOF
```

**Restart network control**
```sh
systemctl restart systemd-networkd.service
systemctl restart systemd-resolved.service
```

#### VPN Connection

You can now connect to the VPN via
```sh
sudo systemctl start openfortivpn@hoerbiger
```

#### VPN Status

As of now, the connection runs in the background and does not need a separate console anymore.
In consequence, to check the current connection status, the following commands are available:
```sh
systemctl status openfortivpn@hoerbiger
networkctl status hoerbiger
```

#### VPN Disconnection

To manually disconnect from VPN, use
```sh
sudo systemctl stop openfortivpn@hoerbiger
```
Additionally, on system shutdown the connection will be terminated as well.


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
**main**
```sh
bin/dep deploy main
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

**main**
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
        
            main:
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

make clone-main
```

## Versioning

This project follows the trunk-based approach for continuous integration.

All branches are directly derived from main and named after the corresponding ticket's number, regardless of whether they're features, bugfixes or anything else.

Ticket branches are independently merged to develop, then staging, then main. All branches except master can at all times be deleted and recreated from master.

### Branching Model

    12345                        +- Commit -+----+----+
                                /            \    \    \
    develop              +------------- Merge -+------------------------> develop
                        /      /                     \    \
    staging      +------------------------- Merge -+-----------------> staging
                /     /      /                              \
    main   ----+-----+------+------------------------ Merge -+----------> master


## Deployment

> To deploy the project, you must be connected to the hoerbiger VPN

Deployment can currently only be done manually and locally.
To do this, connect to the hoerbiger VPN and run the respective commands:

```sh
make deploy-develop

make deploy-staging

make deploy-main
```

## Translation

### Translate all pages from english to a new language
```sh
./flow translation:refresh --source-language en --target-language <target language>
```
### Bypass deepl translation on a test server
By enabling translation bypass, sending translation requests to DeepL is avoided and a suffix (-translated) is added to the original text.
```sh
Hoerbiger:
    HoerbigerCom:
        translation: 
            bypassEnabled: false
```
### Non-translatable properties synchronization
Synchronization strategy is controlled using an option in property definition in node type configuration
```sh
properties:
    foo:    
        ui:        
            options:
                synchronizationStrategy: <once|sync>
```
Default strategy is sync. If a property is to be translated only once when adopted and get separated from the original property the once strategy can be set. 

