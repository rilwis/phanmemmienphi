<?php

function layout(array $vars): void {
	$title       = $vars['title'];
	$description = $vars['description'];
	$basePath    = $vars['basePath'];
	$content     = $vars['content'];
	?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($title) ?></title>
	<meta name="description" content="<?= htmlspecialchars($description) ?>">
	<link rel="stylesheet" href="<?= $basePath ?>css/style.css">
</head>
<body>
	<header class="site-header">
		<div class="container">
			<a href="<?= $basePath ?>index.html" class="site-title">Phần mềm miễn phí</a>
			<nav class="site-nav">
				<a href="<?= $basePath ?>index.html#van-phong">Văn phòng</a>
				<a href="<?= $basePath ?>index.html#tien-ich">Tiện ích</a>
				<a href="<?= $basePath ?>index.html#media">Media</a>
				<a href="<?= $basePath ?>index.html#mang">Mạng</a>
				<a href="<?= $basePath ?>index.html#bao-mat">Bảo mật</a>
			</nav>
		</div>
	</header>

	<main class="site-main">
		<div class="container">
			<?= $content ?>
		</div>
	</main>

	<footer class="site-footer">
		<div class="container">
			<p>Phần mềm miễn phí - Tổng hợp phần mềm miễn phí cho người dùng Việt Nam.</p>
			<p>Website giới thiệu - Không chịu trách nhiệm về nội dung của phần mềm bên thứ ba.</p>
		</div>
	</footer>

</body>
</html>
<?php
}

function renderHomepage(array $categories, array $software, array $colors): string {
	ksort($categories);
	uasort($categories, fn($a, $b) => $a['order'] - $b['order']);

	$html = '<section class="hero">
	<h1>Phần mềm miễn phí</h1>
	<p>Tổng hợp những phần mềm miễn phí tốt nhất cho văn phòng, học sinh, sinh viên và người dùng phổ thông.</p>
</section>';

	$colorIdx = 0;

	foreach ($categories as $catId => $cat) {
		$items = array_values(array_filter($software, fn($s) => $s['cat'] === $catId));
		if (empty($items)) continue;

		$html .= '<section class="category-section" id="' . $catId . '">
	<div class="category-header">
		<div class="category-icon" style="background: ' . $colors[$colorIdx % count($colors)] . '">' . mb_substr($cat['name'], 0, 1) . '</div>
		<h2>' . $cat['name'] . '</h2>
	</div>
	<div class="software-grid">';

		foreach ($items as $sw) {
			$html .= '<a href="p/' . $sw['id'] . '.html" class="software-card">
		<img class="software-icon" src="images/icons/' . $sw['id'] . '.webp" alt="' . htmlspecialchars($sw['name']) . '" width="48" height="48" loading="lazy">
		<div class="software-info">
		<h3>' . htmlspecialchars($sw['name']) . '</h3>
		<p>' . htmlspecialchars($sw['desc']) . '</p>
		</div>
		<span class="arrow">→</span>
	</a>';
			$colorIdx++;
		}

		$html .= '</div></section>';
	}

	return $html;
}

function renderDetail(array $sw, array $cat): string {
	$paras = '';
	foreach (explode("\n\n", $sw['intro']) as $p) {
		$p = trim($p);
		if ($p === '') continue;
		$paras .= '<p>' . nl2br(htmlspecialchars($p)) . "</p>\n";
	}

	$downloadBtn = $sw['download']
		? '<a href="' . htmlspecialchars($sw['download']) . '" class="btn btn-secondary" target="_blank" rel="noopener">
			Tải xuống ↓
		</a>'
		: '';

	return '
<div class="detail-header">
	<div class="breadcrumb">
		<a href="../index.html">Trang chủ</a>
		<span class="separator">/</span>
		<a href="../index.html#' . $sw['cat'] . '">' . htmlspecialchars($cat['name']) . '</a>
		<span class="separator">/</span>
		<span>' . htmlspecialchars($sw['name']) . '</span>
	</div>
	<div class="detail-title">
		<img class="detail-icon" src="../images/icons/' . $sw['id'] . '.webp" alt="' . htmlspecialchars($sw['name']) . '" width="72" height="72">
		<h1 class="detail-name">' . htmlspecialchars($sw['name']) . '</h1>
	</div>
	<p class="detail-description">' . htmlspecialchars($sw['desc']) . '</p>
</div>
<div class="detail-content">
	<div class="detail-body">
		<img class="screenshot-placeholder" src="../images/screenshots/' . $sw['id'] . '.webp" alt="Ảnh chụp màn hình ' . htmlspecialchars($sw['name']) . '" loading="lazy">
		' . $paras . '
	</div>
	<aside class="detail-sidebar">
		<div class="sidebar-card">
		<div class="sidebar-heading">Liên kết</div>
		<div class="btn-group">
			<a href="' . htmlspecialchars($sw['url']) . '" class="btn btn-primary" target="_blank" rel="noopener">
			Trang chủ →
			</a>
			' . $downloadBtn . '
		</div>
		</div>
	</aside>
</div>';
}
