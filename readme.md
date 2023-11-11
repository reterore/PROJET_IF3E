

``` bash
# install php
apt install php php-cli php-mysql
git clone https://github.com/reterore/PROJET_IF3E.git
cd PROJET_IF3E
mysqladmin create space_merchant
cat Creation_db.sql | mysql -C space_merchant 
```