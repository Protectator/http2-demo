# http2-demo 

Compare the differences of performance to serve a same page of your choice using :

* HTTP 1.1
* HTTP/2
* HTTP/2 with PUSHed resources

# Requirements

You'll need :

* An Unix environment
* [Apache](https://httpd.apache.org/) **2.4.17** minimum + PHP5

# Installing

### Disclaimer

Before you install this, please read this :

This is an **experimental** application and it is **not safe** to use in a production environment. Do not run it on a publicly available server.

### App structure

* Clone/Extract/Copy all the content to a directory. Let's say you use `/srv/http2-demo/` for the rest of the explanations.
```bash
mkdir -p /srv/http2-demo/
cd /srv/http2-demo/
```

* Run the script `setup`. It will create required folders `1`, `2` and `2push` that will contain downloaded page's files.

* Give write permissions to the group `www-data` (or the one Apache is running as) for folders `app`, `1`, `2` and `2push`, and execute for folder `app`.
```bash
sudo chgrp -R www-data app 1 2 2push
sudo chmod -R u+xwr *
sudo chmod -R g+wr app 1 2 2push
sudo chmod -R g+x app
```

### Apache Configuration

First, activate `http2_mod` using `sudo a2enmod http2`.

Configure 4 virtual hosts on Apache :

* The first will be for the main application. Its root is the folder `app` of the project, in this case `/srv/http2-demo/app/`.

```
Listen 443
<VirtualHost *:443>
  DocumentRoot /srv/http2-demo/app/
  
  <Directory "/srv/http2-demo/app/">
    Require all granted
    Options +Indexes
  </Directory>
  
  SSLEngine On
  SSLCertificateFile      /etc/ssl/certs/your-cert.pem # Change path to your cert file
  SSLCertificateKeyFile /etc/ssl/private/your-cert.key # Change path to your cert file
</VirtualHost>
```

* The three others will serve the downloaded pages in HTTP 1.1, HTTP/2 and HTTP/2+PUSH. Here, We'll use ports respectively 8081, 8082 and 8083.

Folder `/srv/http2-demo/1` will contain pages served over HTTP 1.1 on port `:8081`

```
Listen 8081
<VirtualHost *:8081>
  DocumentRoot /srv/http2-demo/1/
  
  <Directory "/srv/http2-demo/1/">
    Require all granted
    Options +Indexes
  </Directory>
</VirtualHost>
```

Folder `/srv/http2-demo/2` will contain pages served over HTTP/2 on port `:8082`. Use with SSL.

```
Listen 8082
<VirtualHost *:8082>
  DocumentRoot /srv/http2-demo/2/
  
  <Directory "/srv/http2-demo/2/">
    Require all granted
    Options +Indexes
  </Directory>
  
  SSLEngine On
  SSLCertificateFile      /etc/ssl/certs/your-cert.pem # Change path to your cert file
  SSLCertificateKeyFile /etc/ssl/private/your-cert.key # Change path to your cert file
</VirtualHost>
```

Folder `/srv/http2-demo/2push` will contain pages served over HTTP/2+PUSH enabled on port `:8083`. Use with SSL.

```
Listen 8083
<VirtualHost *:8083>
  DocumentRoot /srv/http2-demo/2push/
  
  <Directory "/srv/http2-demo/2push/">
    Require all granted
    Options +Indexes
  </Directory>
  
  SSLEngine On
  SSLCertificateFile      /etc/ssl/certs/your-cert.pem # Change path to your cert file
  SSLCertificateKeyFile /etc/ssl/private/your-cert.key # Change path to your cert file
</VirtualHost>
```

Then, activate the 4 configurations using `a2ensite` and reload Apache configuration : `sudo service apache2 reload`.

### Enabling set delay from web interface

The bash script `setDelay` needs root permissions. If you want to use it, be sure `www-data` can run `sudo` without password : Run `sudo visudo` and add these lines at the end of the file :
```
User_Alias WWW_USER = www-data
Cmnd_Alias WWW_COMMANDS = /srv/http2-demo/setDelay
WWW_USER ALL = (ALL) NOPASSWD: WWW_COMMANDS
```

# Usage

Access the app using Google Chrome. Enter the address to access the first VirtualHost, the one who's root is `/srv/http2-demo/app/`.
