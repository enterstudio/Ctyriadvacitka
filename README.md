# Čtyřiadvacítka
Vlastní CMS založený na frameworku Nette.
## Instalace
### 1. Vytvoření složky projektu   
    mkdir Ctyriadvacitka   
    cd Ctyriadvacitka
    
### 2. Naklonování repozitáře do nějaké složky   
    git clone git@github.com:krouma/Ctyriadvacitka.git   
### 3. Stažení samotného Nette pomocí composeru   
    composer update   
### 4. Vytvoření složek log a temp/cache    
    mkdir log   
    mkdir -p temp/cache   
### 5. Konfigurace Vagrantu   
#### 5.1 Instalace Vagrantu   
##### 5.1.a Fedora, RHEL, CentOS   
    sudo dnf install vagrant VirtualBox    
#### 5.2 Stažení boxu    
https://drive.google.com/file/d/0B3gkX69GnkCqRnEyWXZhenpOQms/view?usp=sharing   
#### 5.3 Přidání boxu do Vagrantu
    vagrant box add --name ctyriadvacitka cesta/k/jmeno_boxu.box   
### 6. Přidání souboru s přístupovými údaji k databázi   
#### 6.1. Zkopírování souboru do app/cofig/config.local.neon   
    cp config.local.neon app/config/   
#### 6.2. Upravení souboru app/config/config.local.neon na skutečné údaje   
#### 6.3. Soubor app/config/config.local.neon nepřidávat do Gitu
### 7. Přidání záznamu do lokálního DNS serveru.   
##### V Linuxu do souboru /etc/hosts přidat řádek   
    192.168.33.24 ctyriadvacitka.vagrant   
### 8. Konfigurace Sass preprocesoru
#### 8.1 Instalace Ruby    
##### 8.1.a Fedora, RHEL, CentOS    
    sudo dnf install ruby   
##### 8.1.b Debian, Ubuntu, Mint   
    sudo apt-get install ruby   
#### 8.2 Instalace Sass   
    sudo gem install sass    
#### 8.3 Konfigurace IDE   
##### 8.3.a PHPStorm   
1. Otevři Settings/Tools/File Watchers
2. Přidej nový
3. Zvol si název (např. SCSS)
4. Nastav "File type" na SCSS   
5. Nastav cestu k Sassu v "Program" (výchozí v linuxech je /usr/local/bin/scss)
6. Do "Arguments" dej: --no-cache --style compact --update $FileName$:$ProjectFileDir$/www/css/$FileNameWithoutExtension$.css
7. Do "Output paths to refresh" dej: $FileNameWithoutExtension$.css:$FileNameWithoutExtension$.css.map

## Spuštění webu ve Vagrantu
### 1. Ve kořenové složce projektu spustit příkaz   
    vagrant up   
    
### 2. Web je přístupný z adresy ctyriadvacitka.vagrant