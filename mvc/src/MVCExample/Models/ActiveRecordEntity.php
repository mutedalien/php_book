<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 10/28/18
 * Time: 8:09 AM
 */

namespace MVCExample\Models;

use \MVCExample\Services\Db;

/**
 * Class ActiveRecordEntity
 * @package MVCExample\Models
 * Переносим в него универсальный код из класса Article.
 *
 * добавили protected-свойство ->id и public-геттер для него – у всех наших сущностей будет id,
 * и нет необходимости писать это каждый раз в каждой сущности – можно просто унаследовать;
 *
 * перенесли public-метод __set() – теперь все дочерние сущности будут его иметь
 *
 * перенесли метод underscoreToCamelCase(), так как он используется внутри метода __set()
 *
 * public-метод findAll() будет доступен во всех классах-наследниках
 *
 * и, наконец, мы объявили абстрактный protected static метод getTableName(), который должен вернуть строку –
 * имя таблицы. Так как метод абстрактный, то все сущности, которые будут наследоваться от этого класса,
 * должны будут его реализовать. Благодаря этому мы не забудем его добавить в классах-наследниках.
 */
abstract class ActiveRecordEntity
{
    /** @var int */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /************************** __set - Метод для отлова несуществующих свойст с значениями **************************/
    /**
     * Проблему с несоответствием имён легко решить с помощью магического метода __set($name, $value) –
     * если этот метод добавить в класс и попытаться задать ему несуществующее свойство,
     * то вместо динамического добавления такого свойства, будет вызван этот метод. При этом в первый аргумент $name,
     * попадёт имя свойства, а во второй аргумент $value – его значение. А внутри этого метода мы уже сможем решить,
     * что с этими данными делать.
     * В качестве примера давайте добавим в класс Article этот метод. Всё, что он будет делать – это говорить о том,
     * что он был вызван и какие аргументы были в него переданы.
     */
    public function __set(string $name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    /**
     * Функция ucwords() делает первые буквы в словах большими, первым аргументом она принимает строку со словами,
     * вторым аргументом – символ-разделитель (то, что стоит между словами).
     *
     * После этого строка string_with_smth преобразуется к виду String_With_Smth
     * Функция strreplace() заменяет в получившейся строке все символы ‘’ на пустую строку
     * (то есть она просто убирает их из строки). После этого мы получаем строку StringWithSmth
     *
     * Функция lcfirst() просто делает первую букву в строке маленькой. В результате получается строка stringWithSmth.
     * И это значение возвращается этим методом.
     */
    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }
    /**
     * Таким образом, если мы передадим в этот метод строку «created_at», он вернёт нам строку «createdAt»,
     * если передадим «author_id», то он вернёт «authorId». Именно то, что нам нужно!
     */

    /**
     * @return static[]
     * Обратиться к сущности, не создавая её, но чтобы она при этом вернула нам созданные сущности.
     * Вспоминаем статические методы – их ведь можно вызывать, не создавая объекта. То, что нам нужно!
     */
    public static function findAll(): array
    {
        $db = Db::getInstance();
        /**
         * Можно заменить Article::class на self::class – и сюда автоматически подставится класс, в котором этот метод
         * определен. А можно заменить его и вовсе на static::class – тогда будет подставлено имя класса,
         * у которого этот метод был вызван. В чём разница? Если мы создадим класс-наследник SuperArticle,
         * он унаследует этот метод от родителя. Если будет использоваться self:class, то там будет значение “Article”,
         * а если мы напишем static::class, то там уже будет значение “SuperArticle”. Это называется поздним статическим
         * связыванием – благодаря нему мы можем писать код, который будет зависеть от класса, в котором он вызывается,
         * а не в котором он описан.
         */
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    /**
     * @param int $id
     * @return static|null
     * Этот метод вернёт либо один объект, если он найдётся в базе, либо null – что будет говорить об его отсутствии.
     */
    public static function getById(int $id): ?self
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            [':id' => $id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    /**
     * @return string
     * Абстрактный метод у каждой сущности своя таблица в БД.
     */
    abstract protected static function getTableName(): string;
}