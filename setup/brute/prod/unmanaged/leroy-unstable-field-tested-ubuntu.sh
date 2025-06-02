#################################################################################################
#FROM SCRATCH WOWMANAGER INSTALLER FOR UNMANAGED SERVER BRUTE VERSION                           #
#BAKED BY POWTATO################################################################################

#FIELD TESTED ON :
#unmanaged instance, openned standard port for http and ssh
#Ubuntu 24.04.2 LTS
#Release: 24.04

#DEPLOYS :
#   php 8.4.x with symfony required extensions and some more (feel free to adapt)
#   apache2 with standard symfony recommended conf, certificate still to be obtained
#   local mysql server with admin and db user, grants required privileges to dbuser
#   symfony requirements
#   redis
#   git-all
#   wowmanager

#CRUCIAL NOTE:
#EXPERIMENTAL, DOES NOT HANDLE ERROR, CHECK LOGS FOR NOW
#KEEP ME SOMEWHERE, WE HAVE TO KEEP TRACE OF FIRST CLEAN LOOKALIKE LINUX DEMO PROD INSTALL ON UNMANAGED POTATO WITH OPEN PORTS
#THIS SCRIPT SAYS YES TO EVERY INTERACTION.
#STABLE IMPLEMENTATION IN PROGRESS, global todolist in the bottom of the file

##################################################################################################
#BEGIN SCRIPT VARIABLES
#################################################################################################

###########################INPUTS#############################

########MANDATORIES INPUTS

powtatemp=$1
dbname=''
domain=''
mysqldbuserusername=""
mysqldbuserpassword=''
wowmanageradminusername='' 

########OPTIONALS INPUTS

#if mysqlhost is left empty, script will then install mysql localy. 
#note, if external db we'll pass mysqlhost as input to notify script not to install mysql locally.
mysqlhost=''
mysqlport=''
mysqladminusername=''
mysqladminpassword=''
sendcredentialsto=''
wowmanageradminpassword=''
#will generate certificate after successfull install if parameter provided to true (DONT USE IT UNLESS SCRIPT IS DECLARED SAFE, 
#IF YOU DO EXTRACT CERTIFICATE IF YOU GOT IT AND IF SOMETHING WENT WRONG INSTALLING REDEPLOY WITHOUT SETTING THIS PARAM TO TRUE)
needsslsupport='false'

###########################LOCALS#############################

acceptrisks=""

##################################################################################################
#END SCRIPT VARIABLES
##################################################################################################
#BEGIN ENV SETUP
##################################################################################################

#something like checkParams(...), todo check bash syntax

if [ -z "$powtatemp" ]; then
    echo 'temp dir input missing'
    exit 0
fi

cat > /dev/null <<'EOF'
      __      _ |Hello there, i'm leroy, brute version of wowmanager web server deployer  
     /__\__  // |I'M HIGHLY EXPERIMENTAL, I DO NOT HANDLE ERROR, CHECK LOGS AFTERWARDS FOR NOW 
    //_____\/// |POC FIELD TESTED ON Ubuntu 24.04.2 LTS 
   _| /o o\)|/_ |__________________________________________
  (___\ _ //___\
  (  |\\_//  * \\
LEROY WOW MANAGER WEB DEPLOYER
EOF
sleep 3

#todo for manual usage of this brute version, make user accept risk and verify inputs############################
#todo refactor display block echos into a separate file to give code some air
echo "If email not transmited as input, recap of credentials with auto generated default password will 
be available 2 HOURS in $powtatemp/initial_credentials"
echo 'in case, read the file with sudo, consign somewhere safe and get the file out the server ASAP.' 
read -p 'do you understand and accept that this script is dangerous and is to be executed on a plain 
unmanaged server that is easily disposable of ? (y/n):' acceptrisks

#todo make user verify inputs
#todo perform verification on inputs

if [ "$acceptrisks" != "y" ]; then 
    exit 1
fi
echo 'install might take a few minutes. dont forget to check logs afterwards'
##################################################################################################################
sleep 1
cat > /dev/null <<'EOF'
      __      _
     /__\__  //
    //_____\///
   _| />_<\)|/_ LEEEEEEERoooOoOoOoOOOOOOY
  (___\ O //___\ JEEEEEEEEEEEEKIIIIIIIINS
  (  |\\_/// * \\
   \_| \_((*   *))
   ( |__|_\\  *//
   (o/  _  \_*_/
   //\__|__/\
  // |  | |  |
 //  _\ | |___)
//  (___|
EOF
runlocalmysql=false 
if [ -z "$domain" ]; then
    read -p "enter domain url without www (ex:subdomain.domain.org):" domain
fi
if [ -z "$mysqlhost" ]; then
    runlocalmysql=true
    mysqlhost='127.0.0.1'
    if [ -z "$mysqladminusername" ]; then
        read -p "enter mysql admin user name:" mysqladminusername
    fi
    if [ -z "$mysqladminpassword" ]; then
        read -p "enter mysql admin user password:" mysqladminpassword
    fi 
    if [ -z "$mysqldbuserusername" ]; then
        read -p "enter mysql db user name:" mysqldbuserusername
    fi
    if [ -z "$mysqldbuserpassword" ]; then
        read -p "enter mysql db user password:" mysqldbuserpassword
    fi 
    if [ -z "$dbname" ]; then
        read -p "enter mysql db name:" dbname
    fi 
fi
cd $powtatemp

echo 'please consign your password and erase the file right away after install, you can check later if leroy made the job or if your server is wrecked <3.'
echo 'made with <3 by powtato'
sleep 4

#todo don't forget to erase it after ansible implementation (on another script, we need to keep a clean first version on this brute script) 
sudo touch .env
sudo echo 'APP_SECRET="verysecret"' >> .env
sudo echo 'APP_ENV=prod' >> .env
sudo echo "DATABASE_URL=\"mysql://$mysqldbuserusername:$mysqldbuserpassword@$mysqlhost:$mysqlport/$dbname?serverVersion=8.0.42-0ubuntu0.24.04.1\"" >> .env
sudo echo 'MESSENGER_TRANSPORT_DSN=doctrine://default' >> .env
#todo deal with mailer, pass as input
sudo echo 'MAILER_DSN=smtp://admin@powtato.app:password@powtato.app' >> .env
sudo echo 'UPLOAD_PATH=/var/uploads/wowmanager' >> .env

sudo apt update -y

if [ "$runlocalmysql"=="true" ]; then  
    sudo apt install mysql-server -y
    sudo mysql -e "CREATE USER '$mysqladminusername'@'localhost' IDENTIFIED WITH mysql_native_password BY '$mysqladminpassword';"
    sudo mysql -e "GRANT ALL PRIVILEGES ON *.* TO '$mysqladminusername'@'localhost';FLUSH PRIVILEGES;"
    sudo mysql -e "CREATE USER '$mysqldbuserusername'@'localhost' IDENTIFIED WITH mysql_native_password BY '$mysqldbuserpassword';"
    mysql --user=$mysqladminusername --password=$mysqladminpassword -e "CREATE DATABASE $dbname;"    
    sudo mysql -e "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, REFERENCES, DROP ON $dbname.* TO '$mysqldbuserusername'@'localhost';FLUSH PRIVILEGES;"
    sudo ss -tap | grep mysql
fi 

#php and apache
LC_ALL=C.UTF-8 sudo add-apt-repository ppa:ondrej/php -y
sudo apt install php8.4 -y
php -v
sudo apt install php8.4-ctype php8.4-pdo php8.4-intl php8.4-zip php8.4-mysql php8.4-xml php8.4-mbstring php8.4-curl php8.4-zip php8.4-gd php8.4-bcmath php8.4-fpm php8.4-redis php8.4-xml php8.4-dom -y
php -m
sudo a2enmod proxy_fcgi setenvif
sudo apt install php-fpm libapache2-mod-fcgid -y
sudo a2enmod rewrite
sudo a2enmod headers
if [ "$needssl"=="true"]
    sudo ./populate-apache-conf-brute-ssl-ready.sh "$domain" 
else 
    sudo ./populate-apache-conf-brute.sh "$domain"
fi
sudo chmod 777 populate-apache-conf-brute.sh
sudo ./populate-apache-conf-brute.sh "$domain"
rm -f /populate-apache-conf-brute.sh
cat /etc/apache2/sites-available/$domain.conf
sudo a2dissite 000-default
sudo a2ensite $domain.conf
sudo a2enconf wowmanager

#ssl support
if [ "$needsslsupport"=="true" ]; then
    #chmod 777 letsencrypt.sh
    #sudo ./cert-letsencrypt.sh
fi 

#git
sudo apt install git-all -y
sudo git config --global --add safe.directory /var/www/html
#composer
sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
sudo php composer-setup.php
sudo php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
sudo apt-get -y install apt-transport-https -y
#install symfony CLI
sudo curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
sudo apt install symfony-cli -y
#install redis todo POC IT
sudo apt-get install lsb-release curl gpg -y
curl -fsSL https://packages.redis.io/gpg | sudo gpg --dearmor -o /usr/share/keyrings/redis-archive-keyring.gpg
sudo chmod 644 /usr/share/keyrings/redis-archive-keyring.gpg
echo "deb [signed-by=/usr/share/keyrings/redis-archive-keyring.gpg] https://packages.redis.io/deb $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/redis.list
sudo apt-get update
sudo apt-get install redis -y

symfony check:requirements
if [ "$runlocalmysql"=="true" ]; then
    sudo ss -tap | grep mysql
fi    
sleep 2

cd /var/www/html
sudo rm index.html

sudo chown -R www-data:www-data /var/www/html

# Make sure var/ directory is writable (Symfony cache/logs)
sudo chmod -R 775 /var
sudo -u www-data git clone https://github.com/Yricardo/wowmanager.git .
sudo cp $powtatemp/.env .env
chmod 775 .env
sudo rm $powtatemp/.env
sudo -u www-data git checkout master
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php bin/console doc:mig:mig --no-interaction
sudo -u www-data php bin/console asset-map:compile

#todo sudo -u www-data php bin/console admin:globalsettings #(cross bool into dynamic global setting)
#todo sudo -u www-data php bin/console admin:init #(cross bool into dynamic global setting)

if [ -z "$sendcredentialsto" ]; then
    cd $powtatemp
    sudo touch initial_credentials
    if [ "$runlocalmysql"=="true" ]; then
        echo 'mysql :' >> initial_credentials
        echo "  host : $mysqlhost" >> initial_credentials 
        echo "  db admin username : $mysqladminusername" >> initial_credentials 
        echo "  db admin user password : $mysqladminpassword" >> initial_credentials 
        echo "  db user username: $mysqldbuserusername" >> initial_credentials 
        echo "  db user password: $mysqldbuserpassword" >> initial_credentials 
    fi
    echo 'wowmanager:' >> inital_credentials
    echo "  wowmanager admin username : $wowmanageradminusername" >> initial_credentials
    echo "  wowmanager admin password : $wowmanageradminpassword" >> initial_credentials
fi

sudo systemctl restart apache2
php -v
sudo -u www-data composer -v
symfony check:requirements
cd /var/www/html/
git status 
if [ "$runlocalmysql"=="true" ]; then
    mysql -v
    sudo ss -tap | grep mysql
fi

echo 'WOWMANAGER INSTALLED ! test your site and and check your logs for error.'
echo 'if no errors happened in the process, youre all set! :)'
echo "If email not transmited as input, recap of credentials with auto generated default password will be available 2 HOURS in $powtatemp/initial_credentials"
if [ "$needssl"=="true" ]; then
    echo "check for https support to be sure certbot checks went well."
fi    

#########################################################################################################
#                                END SCRIPT
#########################################################################################################
#                               BEGIN FUNCTIONS
#########################################################################################################

#todo implement check params

#########################################################################################################
#                                END FUNCTIONS
#########################################################################################################
#todo handle inputs
#todo for definitive v0 version: 
#support ssl
#todo deal with mailer with variables too, we'll get it as input later
#todo handle errors and prepare report
#todo if no sendcredentialsto plan cron to clean powtatemp after 2hours (be sure to set it writable only by root users using sudo)
#cron will execute sudo rm -rf $powtatemp/
#todo when done rename file and test this self destroy command
#sudo rm -f /etc/setuptemp/leroy-linux.sh
#todo refactor echos at the beginning of the script into a separate file to give code some air
#todo handle mailing in symfony command
