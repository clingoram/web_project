## Laravel購物網站
使用Laradock建置Laravel環境，MySQL

![Cart](/project_images/home.jpg)

<h4>登入</h4>

![cart](/project_images/login.jpg)

<p>依登入使用者身分不同做顯示頁面的區分</p>
<p>不同處:是否有顯示上架頁面<p>

![cart](/project_images/登入成功-不同使用者.jpg)
![cart](/project_images/登入成功.jpg)

<h4>註冊</h4>

![cart](/project_images/register.jpg)

<h4>上傳商品</h4>

![cart](/project_images/upload.jpg)

<h4>結帳</h4>
<p>針對不同帳號，計算各自購物車總金額</p>

![cart](/project_images/cart.jpg)

<hr>
<h5>遇到 商品圖片無法顯示(404)，但path是正確的。</h5>
<p>
上網查是因存取到其他的資料夾，可能我之前有動到甚麼但沒發現，所以storage link還在原先的檔案位置，找不到正確的路徑。</p>
<br>
解法是:<br>
先cd 到public資料夾內，並執行rm storage，之後回到root再php artisan storage:link產生新的storage link才行。
<br>
因為在local環境下，上傳的檔案都存放在 storage 目錄下，用戶是無法看得到的，如果要讓用戶看得到，就要執行"php artisan storage:link"，
為public storage做一個symlink到public底下，以供用戶存取檔案。
之後就可以用asset('url檔案名稱')
