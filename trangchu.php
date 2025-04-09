

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Quản lý khoá học</title>
    <style>
        header {
            text-align: center;
            color: white;
            background-color: pink;
            padding: 20px;
            font-size: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 10px;
            font-size: 15px;
        }
        table {
            width: 100%;
            border: none;
            border-collapse: collapse; /* Gộp viền */
            font-size: 15px;
        }
        tr, td {
            text-align: center;
            border: none;
            color: #333333; 
        }
        td {
            height: 50px;
            overflow: hidden;
        }
        img {
            width: 100px;
            height: 100px;
            display: block;
            margin: 0 auto 10px auto;
        }
        .pagination {
            margin-left: 400px;
            text-decoration: underline;
            color: #000;
        }
        .pagination a {
            font-size: 10px;
            padding: 10px;
            color: blue;
            border-radius: 5px;
            text-align: center;
        }
   
        .sp {
            text-align: center;
            padding-right: 10px;
            padding-left : 10px;
            float: left;
            width: 80%; 
            max-height: 470px; /* Chiều cao tối đa */
        }
        p{
            text-align: center;
            font-size: 16px;
        }
        nav ul {
    list-style-type: none;
    padding: 5px;
    text-align: center;
    color: black;
    margin: 0; /* Xóa khoảng cách mặc định giữa các mục */
}

nav ul li {
    
    margin: 0px 0; /* Khoảng cách dọc giữa các li */
}

nav ul li a {
    padding: 10px;
    text-align: center;
    text-decoration: none; 
    color: #0c046d;
    border-radius: 30px;
    display: block;
    
}

nav ul li a:hover {
    background-color: white; 
    color: #0c046d;
    border-radius: 30px;
    border: 0.1px solid #155724; /* Thêm đường viền màu xanh */
}


        .khoahoc a {
            background-color: #155724; 
            color: white;
            border-radius: 30px;
            display: block;
        
        }
        h1, pre {
            font-size: 15px;
            text-align: left;
            margin-bottom: 20px;
            color: #0c046d;
        }
        h2 {
            font-size: 12px;
            text-align: left;
        }
        p {
            color: #0c046d;
            text-align: center;
            font-size: 16px;
        }
        .bkhoahoc {
            top: -10px;
            margin-bottom: 20px;
            width: 100%;
            background-color: #155724;
            color: white; 
        
        }
 
        .bkhoahoc th {
             margin-top: -10px;
            width: 1000px;
            background-color: #155724;
            color: white;
            padding: 8px;
        
            margin: 0; 
            position: sticky;
            top: 0px;
 
            z-index: 1; /* Đảm bảo phần tiêu đề không bị che */
        }


        tr:hover {
            background-color: #f2f2f2; /* Màu nền khi hover */
            transition: background-color 0.3s; /* Hiệu ứng chuyển màu */

        }
        .search-container {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 20px; 
        }
        .search-container input {
            padding-left: 38px; /* Khoảng cách bên trái cho biểu tượng */
            height: 40px; /* Chiều cao của ô nhập */
            border: 1px solid #ccc; /* Đường viền */
            border-radius: 20px; /* Bo góc */
            width: 300px; /* Chiều rộng của ô nhập */
        }
        .fa-magnifying-glass {
            position: absolute;
            left: 10px;
            color: #999; 
            font-size: 18px; 
        }
        .search-button {
            margin-left: 10px; /* Khoảng cách giữa ô nhập và nút */
            padding: 10px 15px; /* Khoảng cách trong nút */
            border: none; /* Không có đường viền */
            border-radius: 20px; /* Bo góc nút */
            text-decoration: none;
            font-size: 14px;
            background-color: #155724;  /* Màu nền nút */
            color: white; /* Màu chữ */
            cursor: pointer; /* Con trỏ chuột khi hover */
        }
        .search-button:hover {
            background-color: #155724; /* Màu nền khi hover */
        }
        .fa-circle-plus {
            margin-top: -25px;
            margin-left: 300px; 
            padding: 10px 15px; /* Khoảng cách trong nút */
            border: none; /* Không có đường viền */
            border-radius: 20px; /* Bo góc nút */
            background-color: #155724; /* Màu nền nút thêm */
            color: white; /* Màu chữ */
            cursor: pointer; /* Con trỏ chuột khi hover */
            position: absolute;  
        }
        .fa-circle-plus:hover {
            background-color: #155724; 
        }

.loaisp {
    float: left;
    width: 15%;
    padding-right: 15px;
    padding-left : 15px;
    border: 0px solid gray;
    max-height: 500px;
    overflow-y: scroll; 
    scrollbar-width: thin;
    scrollbar-color: transparent transparent; 
}

.loaisp:hover {
    scrollbar-color: #888 #f1f1f1; 
}

.table-container {
    width: 100%;
    float: center;
    border: 0px solid gray;
    height: 340px;
    overflow-y: scroll; 
    scrollbar-width: thin;
    scrollbar-color: transparent transparent;
}

.table-container:hover {
    scrollbar-color: #888 #f1f1f1;
}

.loaisp:-webkit-scrollbar,
.table-container:-webkit-scrollbar {
    width: 5px;
}

.loaisp:-webkit-scrollbar-track,
.table-container:-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 20px;
}

.loaisp:-webkit-scrollbar-thumb,
.table-container:-webkit-scrollbar-thumb {
    background-color: transparent; 
    border-radius: 20px;
    transition: background-color 0.3s ease; 
}

.loaisp:hover:-webkit-scrollbar-thumb,
.table-container:hover:-webkit-scrollbar-thumb {
    background-color: #888; 
    border-radius: 20px;
}



.trang {
    text-align: center; /* Căn giữa */
    margin: 20px 0; /* Khoảng cách trên và dưới */
}

.trang a {
    display: inline-block; /* Hiển thị theo dạng khối */
    padding: 10px 15px; /* Khoảng cách bên trong */
    margin: 0 5px; /* Khoảng cách giữa các liên kết */
    text-decoration: none; /* Bỏ gạch chân */
    color: #155724; /* Màu chữ */
    background-color: #f8f9fa; /* Màu nền */
    border: 1px solid #155724; /* Viền */
    border-radius: 20px; /* Bo góc */
    transition: background-color 0.3s, color 0.3s; /* Hiệu ứng chuyển màu */
}

.trang a:hover {
    background-color: #155724; /* Màu nền khi hover */
    color: white; /* Màu chữ khi hover */
}

.trang span {
    display: inline-block; /* Hiển thị theo dạng khối */
    padding: 10px 15px; /* Khoảng cách bên trong */
    margin: 0 5px; /* Khoảng cách giữa các liên kết */
    background-color: #155724; /* Màu nền cho trang hiện tại */
    color: white; /* Màu chữ cho trang hiện tại */
    border-radius: 20px; /* Bo góc */
}
.icon-container {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 15px;
        }

        .icon-container a {
            color: #155724;
            font-size: 25px;
            text-decoration: none;
        }

        .icon-container a:hover {
            color: #155724;
        }
 .dropdown-account {
    position: relative;
    display: inline-block;
    font-size: 14px;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1000; 
    right: 0; 
    border-radius: 20px;
}

.dropdown-account:hover .dropdown-content {
    display: block;
}

.dropdown-content a {
    color: #155724;
    padding: 15px 18px;
    text-decoration: none;
    display: block;
    font-size: 15px;
}

.dropdown-content a:hover {
   
    border-radius: 20px;
    border: 1px solid #155724;
}




    </style>
</head>
<body>
    <?php
        $i = 1;
        include("connect.php");
    ?>

    <nav class="loaisp">
    <img src="logo-manhha.png" alt="Khoá học" style="width: 100px; height: 60px; "> 
        <p><strong>Quản lý chung</strong></p>
       
        <ul>
            <li class="khoahoc"><a href="Khoahoc.php">Khoá học</a></li>
        </ul>
        <ul>
            <li><a href="Lophoc.php">Lớp học</a></li>
        </ul>
        <ul>
            <li><a href="Giaovien.php">Giáo viên</a></li>
        </ul>
        <ul>
            <li><a href="Trogiang.php">Trợ giảng</a></li>
        </ul>
        <ul>
            <li><a href="Hocvien.php">Học viên</a></li>
        </ul>
        
        <p><strong>Quản lý học viên</strong></p>
        <ul>
            <li><a href="Diemdanh.php">Điểm danh</a></li>
        </ul>
        <ul>
            <li><a href="Baitapvenha.php">Bài tập về nhà</a></li>
        </ul>
        <ul>
            <li><a href="Baikiemtra.php">Bài kiểm tra</a></li>
        </ul>

        <p><strong>Quản lý tài liệu</strong></p>
        <ul>
            <li><a href="DanhSachTaiLieu.php">Danh sách tài liệu</a></li>
        </ul>
        <ul>
            <li><a href="CapTaiLieu.php">Cấp tài liệu</a></li>
        </ul>
    </nav>
    <nav class="sp">
    <div class="icon-container">
    <a  title="Ngôn ngữ"><i class="fas fa-globe"></i></a>
    <a title="Thông báo"><i class="fas fa-bell"></i></a>
    <div class="dropdown-account">
        <a title="Tài khoản"><i class="fas fa-user-circle"></i></a>
        <div class="dropdown-content">
            <a href="user_password.php">Đổi mật khẩu</a>
            <a href="logout.php">Đăng xuất</a>
        </div>

</body>
</html>
    </div>
</div>

