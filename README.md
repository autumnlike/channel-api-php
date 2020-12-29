# チャネルトークのAPI操作コマンド by PHP

## セットアップ

ライブラリのインストール
```
$ composer install
```

環境設定（.env）
```
$ vi .env

# API KEYs
CHANNEL_ACCESS_KEY = 'your access key'
CHANNEL_ACCESS_SECRET = 'your access secret'
```

API credentials の取得方法は [Channel developers page](https://developers.channel.io/docs/openapi-auth) を確認してください。

## 実行

### 指定自動配信IDのチャットを削除する

```
$ php ./Command/execute.php DeleteAllUserChatByCampaignId <配信ID>
```

配信IDは [マーケティング] ページにて確認できます。
![image](https://user-images.githubusercontent.com/5888188/103265522-2284b300-49f1-11eb-97ab-f708c405851b.png)


### 指定一斉配信IDのチャットを削除する

```
$ php ./Command/execute.php DeleteAllUserChatByOneTimeMessageId <配信ID>
```

### REPLでの動作検証コンソール

```
$ php ./Command/console.php
```

## 開発

### デバッグ

```php
breakpoint(); // or bp();
```
