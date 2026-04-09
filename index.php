<?php

require_once ( 'vendor/autoload.php' );

$controller = new DockerList\Controller( 
    \Docker\Docker::create(),
    parse_url($_SERVER['HTTP_HOST'] ?? '', PHP_URL_HOST) ?: 'localhost'
);

$links = $controller->getLinks();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Docker Services</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f6f8fb;
            --panel: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --accent: #0f766e;
            --accent-soft: #e6fffa;
            --border: #dbe3eb;
            --shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", "Helvetica Neue", Helvetica, Arial, sans-serif;
            background:
                radial-gradient(circle at 85% -10%, #d9f4ff 0, transparent 35%),
                radial-gradient(circle at 0 100%, #dcfce7 0, transparent 28%),
                var(--bg);
            color: var(--text);
            background-repeat: no-repeat;
        }

        .page {
            max-width: 960px;
            margin: 0 auto;
            padding: 2rem 1rem 3rem;
            min-height: 100vh;
        }

        header {
            margin-bottom: 1.5rem;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.6rem, 2.4vw, 2.2rem);
            letter-spacing: -0.02em;
        }

        .subtitle {
            margin: 0.5rem 0 0;
            color: var(--muted);
            font-size: 0.98rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem;
            box-shadow: var(--shadow);
        }

        .card h2 {
            margin: 0 0 0.75rem;
            font-size: 1.05rem;
            line-height: 1.2;
        }

        ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: 0.5rem;
        }

        a {
            display: inline-block;
            color: var(--accent);
            text-decoration: none;
            background: var(--accent-soft);
            border: 1px solid #b7f7ee;
            border-radius: 8px;
            padding: 0.45rem 0.6rem;
            word-break: break-all;
            transition: transform 120ms ease, box-shadow 120ms ease;
        }

        a:hover,
        a:focus-visible {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(15, 118, 110, 0.2);
            outline: none;
        }

        .empty {
            background: var(--panel);
            border: 1px dashed var(--border);
            border-radius: 12px;
            padding: 1rem;
            color: var(--muted);
        }
    </style>
</head>

<body>
    <main class="page">
        <header>
            <h1>Docker Service Links</h1>
        </header>

        <?php if ( empty( $links ) ) : ?>
            <section class="empty">No Docker links were found for this host.</section>
        <?php else : ?>
            <section class="grid">
                <?php foreach ( $links as $name => $urls ) : ?>
                    <article class="card">
                        <h2><?php echo htmlspecialchars( (string) $name, ENT_QUOTES, 'UTF-8' ); ?></h2>
                        <ul>
                            <?php foreach ( $urls as $url ) : ?>
                                <?php $safeUrl = htmlspecialchars( (string) $url, ENT_QUOTES, 'UTF-8' ); ?>
                                <li>
                                    <a href="<?php echo $safeUrl; ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo $safeUrl; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>