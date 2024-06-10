<?php
    include '../helpers/user_login_checker.php';

    // cek apakah user yang mengakses adalah admin?
    if (!isAccessAllowed('admin')) {
        session_destroy();
        echo "<meta http-equiv='refresh' content='0;" . base_url_return('index.php?msg=other_error') . "'>";
        return;
    }

    require_once '../vendors/MY_vendors/htmlpurifier/HTMLPurifier.auto.php';
    include_once '../config/connection.php';

    // to sanitize user input
    $config   = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    
    $id_wali_kelas = $_POST['xid_wali_kelas'];
    $nama_kelas    = htmlspecialchars($purifier->purify($_POST['xnama_kelas']));

    $stmt = mysqli_stmt_init($connection);

    mysqli_stmt_prepare($stmt, "INSERT INTO tbl_kelas (id_wali_kelas, nama_kelas) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'is', $id_wali_kelas, $nama_kelas);

    $insert = mysqli_stmt_execute($stmt);

    !$insert
        ? $_SESSION['msg'] = 'other_error'
        : $_SESSION['msg'] = 'save_success';

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

    echo "<meta http-equiv='refresh' content='0;kelas.php?go=kelas'>";
?>