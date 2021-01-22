# Cmatrix
Конструктор платформ.

## Развёртывание
### Linux ###

``` bash
usermod -aG www-data user
usermod -aG user www-data

chmod 775 ..
chmod 775 modiles/Cmatrix/Web/www
```

## Используемые термины
|Термин||
|-|-|
| Система (system) | Общее обозначение проекта, реализованного на платформе Cmatrix |
| Модуль (module) | Самая крупная часть системы, консолидирующая функционал определённой направленности |
| Часть (part) | Элемент модуля, реализующий его атомарный функционал |

### Система (system)

### Модуль (module)

### Часть (part)

----

В фале modules/Cmatrix/Vendor/code/ILess/lib/ILess/Parser/ParserInput.php
в строке 187 continue; заменить на continue 2;

----

2000-2020 [Cmatrix](https://cmatrix.ru) © by ura@itx.ru
