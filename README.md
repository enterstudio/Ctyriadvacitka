# Čtyřiadvacítka
Vlastní CMS založený na frameworku Nette.   
## O aplikaci   
V aplikaci se vyskytují 3 druhy webových stránek   
1. Stránka - má vlastní presenter, není jen statická (např. odesílá formulář)   
2. Článek - statická stránka, pro jejíž aktualizaci není potřeba upravovat zdroják (např. termíny, FAQ),
   jsou uložené v databázi
3. Novinka - speciální druh článku, který se ukládá do vlastní tabulky databáze (např. informace o nadcházející
   výpravě)   

## Automatická instalace
    sudo ./setup.sh
## Ruční instalace

### Závislosti
1. Composer a NPM v systémové PATH
2. Vagrant, VirtualBox
3. doporučené: Git
### 1. Nainstalování Vagrant pluginu   
Použitý Vagrant box v sobě bohužel nemá nainstalované tzv. Guest additions a je proto nutné je ručně
doinstalovat příkazem:
   
    vagrant plugin install vagrant-vbguest   

### 2. Přidání souboru s přístupovými údaji k databázi   
#### 2.1. Zkopírování souboru do app/cofig/config.local.neon   
    cp config.local.neon app/config/   
#### 2.2. Upravení souboru app/config/config.local.neon na skutečné údaje   
#### 2.3. Soubor app/config/config.local.neon nepřidávat do Gitu
### 3. Přidání záznamu do lokálního DNS serveru.   
##### V Linuxu do souboru /etc/hosts přidat řádek   
    192.168.33.24 ctyriadvacitka.vagrant   

## Spuštění Vagrantu
### 1. Ve kořenové složce projektu spustit příkaz   
    vagrant up   
při prvním spuštění u ruční instalace možná budete muset přidat parametr --provider virtualbox    

    vagrant up --provider virtualbox    
### 2. Spustit Node.JS   
    npm start
    
### 3. Web je přístupný z adresy ctyriadvacitka.vagrant

## Licence

MIT

