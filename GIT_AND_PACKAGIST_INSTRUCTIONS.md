# How to Publish Your Plugin to GitHub and Packagist

You have successfully built the **Filament Menu Manager** plugin! Now it's time to share it with the world (or your team) and make it installable via `composer require`.

## Step 1: Push to GitHub

1.  **Create a New Repository on GitHub**:
    *   Go to [github.com/new](https://github.com/new).
    *   Name the repository: `filament-menu-manager`.
    *   Description: "A powerful Filament v4 plugin for managing menus...".
    *   **Do NOT** initialize with a README, license, or .gitignore (we already have these).
    *   Click **Create repository**.

2.  **Push Your Local Code**:
    *   Copy the URL of your new repository (e.g., `https://github.com/notebrainslab/filament-menu-manager.git`).
    *   Run the following commands in your plugin directory (`d:\laragon\www\laravel\filament\filament-menu-manager`):

    ```bash
    git remote add origin https://github.com/notebrainslab/filament-menu-manager.git
    git branch -M main
    git push -u origin main
    ```

3.  **Tag release**:
    *   Tag the version `1.0.0` so Composer can find a stable release.

    ```bash
    git tag v1.0.0
    git push origin v1.0.0
    ```

## Step 2: Publish to Packagist.org

This allows you to run `composer require notebrainslab/filament-menu-manager`.

1.  **Login to [Packagist.org](https://packagist.org/)**.
2.  Click **Submit** in the top menu.
3.  Paste your **GitHub Repository URL** (e.g., `https://github.com/notebrainslab/filament-menu-manager`).
4.  Click **Check**.
5.  If the package name `notebrainslab/filament-menu-manager` is available, click **Submit**.
    *   *Note: If `notebrainslab` is reserved or taken, you might need to rename your package in `composer.json` (e.g., to `your-username/filament-menu-manager`) and commit/push the change before submitting.*

6.  **Set Up Auto-Update (Optional but Recommended)**:
    *   To make Packagist automatically update when you push to GitHub, go to your GitHub repo settings > **Webhooks** > Add webhook.
    *   Payload URL: `https://packagist.org/api/github`
    *   Content type: `application/json`
    *   Secret: (Your Packagist API Token)
    *   Alternatively, just follow the "GitHub Service Hook" instructions on Packagist.

## Step 3: Install in a Project

Once published on Packagist, you can install it in any Laravel application:

```bash
composer require notebrainslab/filament-menu-manager
```

### Private Repository Option (Without Packagist)

If you don't want to publish to Packagist public directory, you can install it directly from GitHub:

1.  In your project's `composer.json`:

    ```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/notebrainslab/filament-menu-manager"
        }
    ]
    ```

2.  Then run:

    ```bash
    composer require notebrainslab/filament-menu-manager
    ```

## Step 4: Verify Installation

After installing, verify the plugin is working:

1.  Run the install command:
    ```bash
    php artisan filament-menu-manager:install
    ```
2.  Register the plugin in your `AdminPanelProvider.php`:
    ```php
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugin(\NoteBrainsLab\FilamentMenuManager\FilamentMenuManagerPlugin::make());
    }
    ```
