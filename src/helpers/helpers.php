<?php

declare(strict_types=1);

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function route_url(string $route, array $params = []): string
{
    return '/index.php?' . http_build_query(array_merge(['route' => $route], $params));
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function format_price(float $price): string
{
    return number_format($price, 0, ',', '.') . ' VND';
}

function old(string $key, string $default = ''): string
{
    return $_POST[$key] ?? $default;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $value = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return $value;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_admin(): bool
{
    return is_logged_in() && (current_user()['role'] ?? 'user') === 'admin';
}

function cart_count(): int
{
    $cart = $_SESSION['cart'] ?? [];
    return (int) array_sum(array_column($cart, 'quantity'));
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-') ?: 'item';
}
