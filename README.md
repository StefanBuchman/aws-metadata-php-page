# Overview #
Queries metadata address on AWS EC2 instances to help see what machine you hit behind an Elastic Load Balancer (ELB)

### Demo Screenshot ###
![Demo screenshot of page](http://www.alphamusk.com/img/demo_aws_metadata.jpg)

### Install required applications
    apt-get install -y apache2 php5 awscli git

### AWS Launch Configuration
    #!/bin/sh

    yum -y install httpd php git
    chkconfig httpd on
    /etc/init.d/httpd start
    cd /var/www/html
    git clone http://github.com/StefanBuchman/aws-metadata-php-page .

### AWS Launch Configuration Base64
    IyEvYmluL3NoDQoNCnl1bSAteSBpbnN0YWxsIGh0dHBkIHBocCBnaXQNCmNoa2NvbmZpZyBodHRwZCBvbg0KL2V0Yy9pbml0LmQvaHR0cGQgc3RhcnQNCmNkIC92YXIvd3d3L2h0bWwNCmdpdCBjbG9uZSBodHRwOi8vZ2l0aHViLmNvbS9TdGVmYW5CdWNobWFuL2F3cy1tZXRhZGF0YS1waHAtcGFnZSAu

### GIT code onto Linux instance
    cd /var/www/html && git clone http://github.com/StefanBuchman/aws-metadata-php-page

### Cron job to git source every 30 mins
    ##### m h  dom mon dow   command
    job="*/30 * * * *  cd /var/www/html && git pull http://github.com/StefanBuchman/aws-metadata-php-page.git > /dev/null 2>&1"
    (crontab -u ${USER} -l; echo "${job}" ) | crontab -u ${USER} -
    crontab -l

### Cron job from script
    (crontab -l 2>/dev/null; echo "*/5 * * * *  cd /var/www/html && git pull http://github.com/StefanBuchman/aws-metadata-php-page.git > /dev/null 2>&1") | crontab -
