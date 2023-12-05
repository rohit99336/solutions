# Deployment

### Auto deploying to a server using .cpanel.yml file

You can use the .cpanel.yml file to automatically deploy your application to your server. This file is a YAML file that specifies the deployment tasks that your server runs when you push your application to your repository.

### Prerequisites

- A repository that contains your application code.
- A server that runs cPanel & WHM version 78 or later.
- A cPanel account on the server that contains your application code.
- A domain that resolves to the cPanel account on the server that contains your application code.

### Procedure

1.  Create a .cpanel.yml file in the root directory of your repository.

2.  Add the following code to the .cpanel.yml file:

    ```yaml
    ---
    deployments:
      - destination: /home/username/public_html
        repo:
          url:
          branch:
        tasks:
          - export DEPLOYPATH=/home/username/public_html
          - /bin/cp -rf $DEPLOYPATH/* $DEPLOYPATH/..cpbackup
          - /bin/cp -rf $DEPLOYPATH/* $DEPLOYPATH/../public_html
          - /bin/rm -rf $DEPLOYPATH/*
          - /bin/cp -rf $DEPLOYPATH/../.cpbackup/* $DEPLOYPATH/
          - /bin/rm -rf $DEPLOYPATH/../.cpbackup
          - /bin/rm -rf $DEPLOYPATH/../public_html
    ```

    or

    ```yaml
    deployment:
    tasks:
      - export DEPLOYPATH=/home/user/public_html/
      - /bin/cp index.html $DEPLOYPATH
      - /bin/cp style.css $DEPLOYPATH
      - /bin/cp -rf images $DEPLOYPATH
    ```

3.  Replace the following variables in the code:

        -   **username** — The cPanel account username.
        -   **url** — The URL of the repository that contains your application code.
        -   **branch** — The branch of the repository that contains your application code.

4.  Commit and push the .cpanel.yml file to your repository.

5.  Navigate to the cPanel account's domain.

6.  Click the **Deploy** button.

7.  Click **Deploy HEAD Commit**.

8.  Click **Deploy HEAD Commit** again.

9.  Click **View Deployment Log** to view the deployment log.

10. Click **Close** to close the deployment log.

### Additional Documentation

- [The .cpanel.yml file](https://go.cpanel.net/cpanelyml)
