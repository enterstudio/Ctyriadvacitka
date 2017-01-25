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
### 5. Přidání Vagrant boxu   
    vagrant box add --name ctyriadvacitka cesta/k/jmeno_boxu.box   
### 6. Přidání souboru s přístupovými údaji k databázi   
#### 6.1. Zkopírování souboru do app/cofig/config.local.neon   
    cp config.local.neon app/config/   
#### 6.2. Upravení souboru app/config/config.local.neon na skutečné údaje   
#### 6.3. Soubor app/config/config.local.neon nepřidávat do Gitu
### 7. Přidání záznamu do lokálního DNS serveru.   
##### V Linuxu do souboru /etc/hosts přidat řádek   
    192.168.33.24 ctyriadvacitka.vagrant   
## Spuštění webu ve Vagrantu
### 1. Ve kořenové složce projektu spustit příkaz   
    vagrant up   
    
### 2. Web je přístupný z adresy ctyriadvacitka.vagrant