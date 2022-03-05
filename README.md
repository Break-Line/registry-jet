# registry-jet


### Installation:

You will need Docker and its docker-compose cli.
In order to simplify that process, we will install Docker Desktop.

- Install [Docker Desktop](https://www.docker.com/products/docker-desktop)
(If you are a Windows user, then you will need to install also the [WSL](https://docs.microsoft.com/it-it/windows/wsl/install-manual#step-4---download-the-linux-kernel-update-package))
- Copy `.env.example` to `.env`
- Run `docker-compose up`
- In your browser, visit `localhost:8081`

You can login with `admin@example.org` or `user@example.org` using password `password`

# Enjoy!


### Debug

In Registry Jet the debug comes out of the box. You will just need to install the XDebug extension for your IDE and then listen to 9003 port.

#### Using VSCode:

Create a folder named `.vscode` in the project's root directory, and then create a file named `launch.json` inside it.
You can use this simple `launch.json` file in order to get debug working:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug on Registry Jet",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html/": "${workspaceFolder}"
            }
        }
    ]
}
```
