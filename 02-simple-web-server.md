# HTTPS - Web App

## Configure an Apache web server that meets the following requirements.

1. Install the httpd server
2. Expose the container port 80.
3. Create an index.html file with test contents, in the default document root.
4. Is accessible to any user without any further restrictions.

## Apache Configuration files

1. Main configuration is in /etc/httpd/conf/httpd.conf
   - ServerRoot "/etc/httpd", all file names relate to that
2. Module configuration is in /etc/httpd/conf.modules.d
   - Include conf.modules.d/*.conf
3. Supplemental configuration is in /etc/httpd/conf.d/*.conf
   - Include Optional conf.d/*.conf
4. Default DocumentRoot is "/var/www/html"


<details><summary>Simple WebServer Commands - HTTP</summary>
<p>

1. Apache check if already installed:

```bash
which httpd
rpm -qf /sbin/httpd
rpm -qc httpd
cd /etc/sysconfig/
ls
vim httpd

cd /etc/httpd
ls
cd conf
ls
```
```bash
vim httpd.conf
...
Listen 80
include conf.modules.d/*.conf
DocumentRoot 
```

2. Apache Installation:

```bash
mkdir /web
yum install -y httpd
cd /etc/httpd/
ls
cd conf
```
Change entry for the DocumentRoot:
```bash
vim httpd.conf
...
#DocumentRoot "/var/www/html"
DocumentRoot "/web"
...
#<Directory "/var/www/html">
<Directory "/web">
...
:wq
```
```bash
cd /web
vim index.html
<blink>Sri Ramajeyam...!</blink>
:wq
```
Change the SELinux context.
```bash
cd /
ls -Zd /web

ls -Zd /var/www/html

semanage fcontext -a -t httpd_sys_content_t '/web(/.*)?'

or

chcon -t httpd_sys_content_t /web



```

```bash
systemctl restart httpd
systemctl enable httpd
systemctl status -l httpd
firewall-cmd --permanent --add-service=http --add-service=https
firewall-cmd --reload
```

3. Now Check the index page using web browser with in the system.

```bash
curl localhost
wget -O- localhost:80
elinks http://localhost:80
```

</p>
</details>


4. Granting Permission to developers.

```bash
groupadd webdev
chgrp webdev /web
setfacl -R -m g:webdev:rwX /web
setfacl -R -m d:g:webdev:rwX /web
getfacl /web

# create a development user and add him to the group webdev
useradd devuser
passwd devuser
usermod -aG webdev devuser
```

## SSL - TLS Webserver

1. TLS adds security
   - Data Encryption
   - Identity verification
2. Central role for certificates
   - Signature that is guaranteed by a CA
3. Two types of certificates
   - Self signed - okay for testing
   - Signed by CA authority, required for production


<details><summary>Webserver TLS - SSL commands - HTTPS</summary>
<p>

Install packages to Generate Keys and self signed certificate:

``` bash
yum -y install crypto-utils
yum install -y mod_ssl
genkey server1.example.com
...
--> press next - for keypair generation
--> press next - choose key size
--> will generate randam bits
--> Press no - Generate CSR - certificate request need not to send. since we are creating self signed key.
--> Press next - without selecting the option encrypt the private key under Protecting your private key
--> Press next - since Common Name: FQDN is correct in the Enter details for your certificates screen
```

``` bash
cd /etc/pki/tls/private/
ls -Z

cd .. /certs
ls -Z
```


``` bash
cd /etc/httpd/conf.d/
ls
vim ssl.conf
...
Listen 443 https
...
#SSLCertificateFile /etc/pki/tls/certs/localhost.crt
SSLCertificateFile /etc/pki/tls/certs/server1.example.com.crt
...
#SSLCertificateKeyFile /etc/pki/tls/private/localhost.key
SSLCertificateKeyFile /etc/pki/tls/private/server1.example.com.key
```

Restart Apache web server.

``` bash
systemctl restart httpd
systemctl status httpd
```


``` bash
firefox https://localhost
...
confirm the security certificate - since it is self signed
...
```
</p>
</details>
