## 開発環境セットアップ

### 1.プロジェクトをclone
```bash
git clone https://github.com/tahoito/cafest_app.git
cd cafest
```

### 2.Dockerを起動する
```bash
docker compose build
docker compose up -d
```
### 3.Laravelをセットアップする
```bash
docker compose exec app bash
cd my-app
cp .env.example .env
php artisan key:generate
```

### 4.DBマイグレーション
```bash
php artisan migrate
```

## 開発フロー

### 1.mainを最新にする
```bash
git checkout main
git pull origin main
```

### 2.ブランチを作成する
```bash
git checkout -b feature/機能名
```

### 3.コミットする
```bash
git add .
git commit -m "ホーム画面を追加"
```

### 4.pushする
```bash
git push -u origin feature/機能名
```


