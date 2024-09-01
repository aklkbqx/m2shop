<?php
session_start();
require_once __DIR__ . "/../lib/util.php";

if (isset($_GET["login"])) {
    $response = [];

    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $response['success'] = false;
        $response['message'] = 'กรุณากรอกข้อมูลให้ครบ!';
    } else {
        $check = sql("SELECT * FROM `users` WHERE `email` = ?", [$email]);
        if ($check->rowCount() > 0) {
            $row = $check->fetch(PDO::FETCH_ASSOC);
            if ($email == $row['email'] && password_verify($password, $row['password'])) {
                $_SESSION[$row['role'] . '_login'] = $row['user_id'];
                if ($row['role'] == "admin") {
                    $response['role'] = "admin";
                } else {
                    $response['role'] = "user";
                }
                $response['success'] = true;
                $response['message'] = 'เข้าสู่ระบบสำเร็จ!';
            } else {
                $response['success'] = false;
                $response['message'] = 'รหัสผ่านไม่ถูกต้อง!';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'ไม่มีข้อมูลในระบบ!';
        }
    }
    echo json_encode($response);
}
if (isset($_GET["register"])) {
    $response = [];

    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $tel = $_POST["tel"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $c_password = $_POST["c_password"];

    if (empty($firstname) || empty($lastname) || empty($tel) || empty($address) || empty($email) || empty($password) || empty($c_password)) {
        $response['success'] = false;
        $response['message'] = 'กรุณากรอกข้อมูลให้ครบทุกช่อง!';
    } else {
        if ($password != $c_password) {
            $response['success'] = false;
            $response['message'] = 'รหัสผ่านไม่ตรงกัน!';
        } else {
            $profileImage = 'default-profile.jpg';
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $insert = sql("INSERT INTO `users`(`firstname`,`lastname`,tel,`email`,`password`,`profile_image`,`address`) VALUES(?,?,?,?,?,?,?)", [
                $firstname,
                $lastname,
                $tel,
                $email,
                $passwordHash,
                $profileImage,
                $address
            ]);

            if ($insert) {
                $lastId = $pdo->lastInsertId();
                $stmt = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$lastId]);

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $_SESSION[$row['role'] . '_login'] = $row['user_id'];

                    $response['success'] = true;
                    $response['message'] = 'สมัครสมาชิกเสร็จสิ้น!';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'เกิดข้อผิดพลาดในการสมัครสมาชิก!';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล!';
            }
        }
    }
    echo json_encode($response);
}
if (isset($_GET["edit-profile"]) && isset($_GET["user_id"])) {
    $response = [];

    $user_id = $_GET["user_id"];
    $row = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$user_id])->fetch(PDO::FETCH_ASSOC);

    $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : null;
    $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
    $c_password = isset($_POST["c_password"]) ? $_POST["c_password"] : null;
    $address = isset($_POST["address"]) ? $_POST["address"] : null;
    $tel = isset($_POST["tel"]) ? $_POST["tel"] : null;
    $old_password = isset($_POST["old_password"]) ? $_POST["old_password"] : null;

    $profileImage = $row["profile_image"];
    $path = '../assets/images/user_images/';

    if (!empty($_FILES['profile']['name'])) {
        $profile = $_FILES['profile']['name'];
        $tmp_name = $_FILES['profile']['tmp_name'];
        $extension = pathinfo($profile, PATHINFO_EXTENSION);
        $profileImage = uniqid() . '.' . $extension;

        if (!empty($tmp_name)) {
            if ($row["profile_image"] != "default-profile.jpg" && file_exists($path . $row["profile_image"])) {
                unlink($path . $row["profile_image"]);
            }
            move_uploaded_file($tmp_name, $path . $profileImage);
        } else {
            $response["success"] = false;
            $response["message"] = "ไฟล์รูปภาพมีปัญหา กรุณาลองใหม่อีกครั้ง!";
            echo json_encode($response);
            exit;
        }
    }

    if (!empty($password) && $password == $c_password) {
        if ($row["role"] == "user" && !empty($old_password) && !password_verify($old_password, $row["password"])) {
            $response["success"] = false;
            $response["message"] = "รหัสผ่านเดิมไม่ถูกต้อง!";
            echo json_encode($response);
            exit;
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    } else if (!empty($password) && $password != $c_password) {
        $response["success"] = false;
        $response["message"] = "รหัสผ่านไม่ตรงกัน!";
        echo json_encode($response);
        exit;
    }

    $fieldsToUpdate = [];
    $valuesToUpdate = [];

    if ($firstname) {
        $fieldsToUpdate[] = "`firstname` = ?";
        $valuesToUpdate[] = $firstname;
    }
    if ($lastname) {
        $fieldsToUpdate[] = "`lastname` = ?";
        $valuesToUpdate[] = $lastname;
    }
    if ($email) {
        $fieldsToUpdate[] = "`email` = ?";
        $valuesToUpdate[] = $email;
    }
    if ($tel) {
        $fieldsToUpdate[] = "`tel` = ?";
        $valuesToUpdate[] = $tel;
    }
    if ($address) {
        $fieldsToUpdate[] = "`address` = ?";
        $valuesToUpdate[] = $address;
    }
    if (isset($passwordHash)) {
        $fieldsToUpdate[] = "`password` = ?";
        $valuesToUpdate[] = $passwordHash;
    }
    if ($profileImage) {
        $fieldsToUpdate[] = "`profile_image` = ?";
        $valuesToUpdate[] = $profileImage;
    }

    if (!empty($fieldsToUpdate)) {
        $valuesToUpdate[] = $user_id;
        $sql = "UPDATE `users` SET " . implode(", ", $fieldsToUpdate) . " WHERE `user_id` = ?";
        $update = sql($sql, $valuesToUpdate);

        if ($update) {
            $response["success"] = true;
            $response["message"] = "แก้ไขข้อมูลเสร็จสิ้น!";
        }
    } else {
        $response["success"] = false;
        $response["message"] = "ไม่มีข้อมูลที่จะอัปเดต!";
    }

    echo json_encode($response);
}

if (isset($_REQUEST["add_user"]) && isset($_GET["add"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $tel = $_POST["tel"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $c_password = $_POST["c_password"];
    $address = $_POST["address"];

    $profile = $_FILES['profile']['name'];
    $tmp_name = $_FILES['profile']['tmp_name'];
    $path = '../assets/images/user_images/';

    if ($password != $c_password) {
        msg('รหัสผ่านไม่ตรงกัน!', 'danger', $_SERVER['HTTP_REFERER']);
    } else {
        if (!empty($profile)) {
            $extension = pathinfo($profile, PATHINFO_EXTENSION);
            $profileImage = uniqid() . '.' . $extension;
            if (empty($tmp_name)) {
                msg('ไฟล์รูปภาพมีปัญหา กรุณาลองใหม่อีกครั้ง!', 'danger', $_SERVER['HTTP_REFERER']);
            } else {
                move_uploaded_file($tmp_name, $path . $profileImage);
            }
        } else {
            $profileImage = 'default-profile.jpg';
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $insert = sql("INSERT INTO `users`(`firstname`,`lastname`,tel,`email`,`password`,`profile_image`,`address`) VALUES(?,?,?,?,?,?,?)", [
            $firstname,
            $lastname,
            $tel,
            $email,
            $passwordHash,
            $profileImage,
            $address
        ]);
        if ($insert) {
            msg('เพิ่มสมาชิกเสร็จสิ้น!', 'success', $_SERVER['HTTP_REFERER']);
        }
    }
}

if (isset($_REQUEST["save-editUser"]) && isset($_GET["edit"]) && isset($_GET["user_id"])) {
    $firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : null;
    $lastname = isset($_POST["lastname"]) ? $_POST["lastname"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $password = isset($_POST["password"]) ? $_POST["password"] : null;
    $c_password = isset($_POST["c_password"]) ? $_POST["c_password"] : null;

    $address = isset($_POST["address"]) ? $_POST["address"] : null;
    $tel = isset($_POST["tel"]) ? $_POST["tel"] : null;

    $profile = $_FILES['profile']['name'];
    $tmp_name = $_FILES['profile']['tmp_name'];
    $path = '../assets/images/user_images/';

    $user_id = $_GET["user_id"];
    $row = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$user_id])->fetch(PDO::FETCH_ASSOC);

    if (!empty($profile)) {
        $extension = pathinfo($profile, PATHINFO_EXTENSION);
        $profileImage = uniqid() . '.' . $extension;
        if (empty($tmp_name)) {
            msg('ไฟล์รูปภาพมีปัญหา กรุณาลองใหม่อีกครั้ง!', 'danger', $_SERVER['HTTP_REFERER']);
        } else {
            if ($row["profile_image"] != "default-profile.jpg") {
                if (file_exists($path . $row["profile_image"])) {
                    unlink($path . $row["profile_image"]);
                }
            }
            move_uploaded_file($tmp_name, $path . $profileImage);
        }
    } else {
        $profileImage =  $row["profile_image"];
    }

    if (!empty($password) && $password == $c_password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    } else if (!empty($password) && $password != $c_password) {
        msg('รหัสผ่านไม่ตรงกัน!', 'danger', $_SERVER['HTTP_REFERER']);
    }

    $fieldsToUpdate = [];
    $valuesToUpdate = [];

    if ($firstname) {
        $fieldsToUpdate[] = "`firstname` = ?";
        $valuesToUpdate[] = $firstname;
    }
    if ($lastname) {
        $fieldsToUpdate[] = "`lastname` = ?";
        $valuesToUpdate[] = $lastname;
    }
    if ($email) {
        $fieldsToUpdate[] = "`email` = ?";
        $valuesToUpdate[] = $email;
    }
    if ($tel) {
        $fieldsToUpdate[] = "`tel` = ?";
        $valuesToUpdate[] = $tel;
    }
    if ($address) {
        $fieldsToUpdate[] = "`address` = ?";
        $valuesToUpdate[] = $address;
    }
    if (isset($passwordHash)) {
        $fieldsToUpdate[] = "`password` = ?";
        $valuesToUpdate[] = $passwordHash;
    }
    if ($profileImage) {
        $fieldsToUpdate[] = "`profile_image` = ?";
        $valuesToUpdate[] = $profileImage;
    }

    if (!empty($fieldsToUpdate)) {
        $valuesToUpdate[] = $user_id;
        $sql = "UPDATE `users` SET " . implode(", ", $fieldsToUpdate) . " WHERE `user_id` = ?";
        $update = sql($sql, $valuesToUpdate);

        if ($update) {
            msg('แก้ไขข้อมูลเสร็จสิ้น!', 'success', $_SERVER['HTTP_REFERER']);
        }
    } else {
        msg('ไม่มีข้อมูลที่จะอัปเดต!', 'warning', $_SERVER['HTTP_REFERER']);
    }
}

if (isset($_REQUEST["delete_user"]) && isset($_GET["delete"]) && isset($_GET["user_id"])) {
    $path = '../assets/images/user_images/';

    $user_id = $_GET["user_id"];
    $row = sql("SELECT * FROM `users` WHERE `user_id` = ?", [$user_id])->fetch(PDO::FETCH_ASSOC);

    if ($row["profile_image"] != "default-profile.jpg") {
        if (file_exists($path . $row["profile_image"])) {
            unlink($path . $row["profile_image"]);
        }
    }
    $delete = sql("DELETE FROM `users` WHERE `user_id` = ?", [$user_id]);

    if ($delete) {
        msg('ลบบัญชีเสร็จสิ้น!', 'success', $_SERVER['HTTP_REFERER']);
    }
}
