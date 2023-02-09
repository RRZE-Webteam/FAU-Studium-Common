<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Dashboard\AdminBar;

use Closure;

final class AdminPostAction
{
    private function __construct(
        private string $hookName,
        private Closure $callable,
        private string $successfulMessage,
        private string $errorMessage,
    ) {
    }

    public static function new(
        string $hookName,
        Closure $callable
    ): self {

        return new self($hookName, $callable, '', '');
    }

    public function withSuccessMessage(string $message): self
    {
        return new self(
            $this->hookName,
            $this->callable,
            $message,
            $this->errorMessage
        );
    }

    public function withErrorMessage(string $message): self
    {
        return new self(
            $this->hookName,
            $this->callable,
            $this->successfulMessage,
            $message
        );
    }

    public function buildUrl(): string
    {
        $queryArgs = [
            'action' => $this->hookName,
        ];

        $referer = remove_query_arg($this->hookName);
        if ($referer) {
            $queryArgs['_wp_http_referer'] = urlencode($referer);
        }

        return wp_nonce_url(
            add_query_arg($queryArgs, admin_url('admin-post.php')),
            $this->hookName
        );
    }

    public function register(): void
    {
        add_action(
            'admin_post_' . $this->hookName,
            $this->buildCallback()
        );

        add_action('admin_notices', function () {
            $this->printNotice();
        });
    }

    private function buildCallback(): callable
    {
        return function (): void {
            check_admin_referer($this->hookName);

            $result = ($this->callable)();

            wp_safe_redirect(
                add_query_arg([$this->hookName => $result], wp_get_referer())
            );
            exit;
        };
    }

    private function printNotice(): void
    {
        $result = filter_input(INPUT_GET, $this->hookName, FILTER_VALIDATE_BOOL);
        if ($result === null) {
            return;
        }

        $message = $result ? $this->successfulMessage : $this->errorMessage;
        if (!$message) {
            return;
        }

        printf(
            '<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
            esc_attr($result ? 'success' : 'error'),
            esc_html($message)
        );
    }
}
