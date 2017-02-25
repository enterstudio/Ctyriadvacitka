# Čtyřiadvacítka
Vlastní CMS založený na frameworku Nette.   
## O aplikaci   
V aplikaci se vyskytují 3 druhy webových stránek   
1. Stránka - má vlastní presenter, není jen statická (např. odesílá formulář)   
2. Článek - statická stránka, pro jejíž aktualizaci není potřeba upravovat zdroják (např. termíny, FAQ),
   jsou uložené v databázi
3. Novinka - speciální druh článku, který se ukládá do vlastní tabulky databáze (např. informace o nadcházející
   výpravě)   

## Závislosti
1. Composer a NPM v systémové PATH
2. Vagrant, VirtualBox
3. doporučené: Git

## Instalace
### 1. Vytvoření složky projektu   
    mkdir Ctyriadvacitka   
    cd Ctyriadvacitka
    
### 2. Naklonování repozitáře do nějaké složky   
    git clone git@github.com:krouma/Ctyriadvacitka.git   
### 3. Stažení knihoven, vytvoření potřebných souborů a složek   
    npm run install    
### 4. Přidání souboru s přístupovými údaji k databázi   
#### 4.1. Zkopírování souboru do app/cofig/config.local.neon   
    cp config.local.neon app/config/   
#### 4.2. Upravení souboru app/config/config.local.neon na skutečné údaje   
#### 4.3. Soubor app/config/config.local.neon nepřidávat do Gitu
### 5. Přidání záznamu do lokálního DNS serveru.   
##### V Linuxu do souboru /etc/hosts přidat řádek   
    192.168.33.24 ctyriadvacitka.vagrant   

## Spuštění webu ve Vagrantu
### 1. Ve kořenové složce projektu spustit příkaz   
    vagrant up   
při prvním spuštění možná budete muset přidat parametr --provider virtualbox    

    vagrant up --provider virtualbox    
### 2. Spustit Node.JS   
    npm start
    
### 3. Web je přístupný z adresy ctyriadvacitka.vagrant

## Changelog Vagrantboxu
- v2.3 - Aktualizováno PHP na verzi 7.0.15

## Licence

MIT

