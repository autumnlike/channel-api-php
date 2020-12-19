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

### 指定配信IDのチャットを削除する

```
$ php Command/DeleteAllUserChatByCampaignId.php 12345<配信ID>
```
