# Laravel Bloom Filter

<hr>

### 1.通过composer安装
```shell
composer require jacksmall/bloomfilter
```
### 2.发布配置文件
```shell
php artisan vendor:publish --provider="Jacksmall\Bloomfilter\Providers\BloomfilterServiceProvider" --tag="bloomfilter-config"
```
### 3.在本地项目config/app.php
```
'providers' => [
    ...
    \Jacksmall\Bloomfilter\Providers\BloomFilterServiceProvider::class
    ...
]
```
### 4.使用
```php
use Jacksmall\Bloomfilter\Facades\BloomFilter;

// 添加元素
BloomFilter::add('user@example.com');
BloomFilter::addMany(['item1', 'item2']);

// 检查元素是否存在
if (BloomFilter::exists('user@example.com')) {
    // 可能存在（有误判概率）
} else {
    // 一定不存在
}

BloomFilter::connection('users')->add($user->id);
```

## 😄😄