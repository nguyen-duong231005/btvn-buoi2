<?php
// 1. Viết hàm kiểm tra điểm hợp lệ (phải là số và nằm trong khoảng 0 - 10)
function isValidScore($score): bool {
    return is_numeric($score) && $score >= 0 && $score <= 10;
}

// 2. Viết hàm tính điểm tổng kết: 10% chuyên cần, 30% giữa kỳ, 60% cuối kỳ
function finalScore(float $cc, float $mid, float $final): float {
    return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
}

// Khai báo các biến lưu trữ dữ liệu và thông báo lỗi
$fullname = $_POST['fullname'] ?? '';
$cc = $_POST['cc'] ?? '';
$mid = $_POST['mid'] ?? '';
$final = $_POST['final'] ?? '';

$errors = [];
$result = null;

// 3. Sử dụng POST để xử lý gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation
    if (trim($fullname) === '') {
        $errors[] = "Vui lòng nhập họ tên.";
    }

    if (!isValidScore($cc)) {
        $errors[] = "Điểm chuyên cần không hợp lệ! Vui lòng nhập từ 0 đến 10.";
    }

    if (!isValidScore($mid)) {
        $errors[] = "Điểm giữa kỳ không hợp lệ! Vui lòng nhập từ 0 đến 10.";
    }

    if (!isValidScore($final)) {
        $errors[] = "Điểm cuối kỳ không hợp lệ! Vui lòng nhập từ 0 đến 10.";
    }

    // Nếu không có lỗi thì thực hiện tính toán và xếp loại
    if (empty($errors)) {
        $final_score = finalScore((float)$cc, (float)$mid, (float)$final);

        // Xếp loại: giỏi (>=8), khá (>=6.5), trung bình (>=5), chưa đạt (<5)
        if ($final_score >= 8.0) {
            $rank = "Giỏi";
        } elseif ($final_score >= 6.5) {
            $rank = "Khá";
        } elseif ($final_score >= 5.0) {
            $rank = "Trung bình";
        } else {
            $rank = "Chưa đạt";
        }

        $result = [
            'fullname' => $fullname,
            'final_score' => number_format($final_score, 2),
            'rank' => $rank
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tính điểm tổng kết và Xếp loại</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .form-group { margin-bottom: 12px; }
        label { display: inline-block; width: 160px; }
        input[type="text"], input[type="number"] { padding: 5px; width: 220px; }
        .error { color: red; margin-bottom: 15px; }
        .result { background: #eef; padding: 15px; border-radius: 5px; margin-top: 20px; width: 380px; }
    </style>
</head>
<body>

    <h2>Tính Điểm Tổng Kết Học Phần</h2>

    <!-- Hiển thị danh sách lỗi nếu điểm không nằm trong khoảng 0–10 -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form gửi dữ liệu bằng POST, giữ lại dữ liệu đã nhập khi có lỗi -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="fullname">Họ và tên:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
        </div>
        <div class="form-group">
            <label for="cc">Điểm chuyên cần (10%):</label>
            <input type="number" step="0.1" id="cc" name="cc" value="<?php echo htmlspecialchars($cc); ?>" min="0" max="10" required>
        </div>
        <div class="form-group">
            <label for="mid">Điểm giữa kỳ (30%):</label>
            <input type="number" step="0.1" id="mid" name="mid" value="<?php echo htmlspecialchars($mid); ?>" min="0" max="10" required>
        </div>
        <div class="form-group">
            <label for="final">Điểm cuối kỳ (60%):</label>
            <input type="number" step="0.1" id="final" name="final" value="<?php echo htmlspecialchars($final); ?>" min="0" max="10" required>
        </div>
        <button type="submit">Tính kết quả</button>
    </form>

    <!-- Hiển thị kết quả -->
    <?php if ($result): ?>
        <div class="result">
            <h3>Kết Quả Học Tập</h3>
            <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($result['fullname']); ?></p>
            <p><strong>Điểm tổng kết:</strong> <?php echo $result['final_score']; ?></p>
            <p><strong>Xếp loại:</strong> <?php echo $result['rank']; ?></p>
        </div>
    <?php endif; ?>

</body>
</html>