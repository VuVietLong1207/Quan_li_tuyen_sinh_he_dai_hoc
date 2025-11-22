<?php
session_start();
require_once '../../functions/db_connection.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT n.*, u.full_name 
    FROM news n 
    JOIN users u ON n.author_id = u.id 
    WHERE n.id = ? AND n.is_published = TRUE
");
$stmt->execute([$id]);
$news = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$news) {
    header('Location: /BTL/views/public/news.php');
    exit();
}
?>

<?php include '../partials/header.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/BTL/index.php">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="/BTL/views/public/news.php">Tin tức</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($news['title']); ?></li>
                    </ol>
                </nav>

                <article>
                    <header class="mb-4">
                        <span class="badge bg-primary mb-2">
                            <?php 
                            $categories = [
                                'tuyensinh' => 'Tuyển sinh',
                                'sukien' => 'Sự kiện', 
                                'thongbao' => 'Thông báo'
                            ];
                            echo $categories[$news['category']] ?? $news['category'];
                            ?>
                        </span>
                        <h1 class="fw-bold"><?php echo htmlspecialchars($news['title']); ?></h1>
                        <div class="text-muted mb-3">
                            <i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($news['full_name']); ?> | 
                            <i class="fas fa-calendar me-1"></i> <?php echo date('d/m/Y H:i', strtotime($news['published_at'])); ?>
                        </div>
                    </header>

                    <div class="news-content">
                        <?php echo nl2br(htmlspecialchars($news['content'])); ?>
                    </div>
                </article>

                <div class="mt-5">
                    <a href="/BTL/views/public/news.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại tin tức
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../partials/footer.php'; ?>