# Longterm internet storage of tasks

Learning management system with longterm tasks persistance.

## Application setup:
### Windows:
1. Install [Docker Desktop](https://docs.docker.com/desktop/install/windows-install/) and [WSL](https://learn.microsoft.com/en-us/windows/wsl/install).
2. Enable integration with ***WSL*** in the ***Docker desktop*** settings.
3. Add the following line: ```127.0.0.1 server.listdev``` to the file: 

   > ...\Windows\System32\drivers\etc\hosts
   
4. Clone this repository to the ***WSL*** file system.
5. In the root directory of the project, call the `docker-compose up -d` command.
6. Call `docker-compose exec list-server bash` command and then run a file ***initDependencies*** with the command `sudo ./initDependencies`.
7. Run `bin/console update_database` command.
8. Create a new teacher to be able to log into the system with the `bin/console new_teacher` command.
9. The server should now be running at [http://server.listdev/admin](http://server.listdev/admin), you can now log into it using the credentials entered when creating the new teacher in the previous step.

### Linux:
1. Install [Docker Engine](https://docs.docker.com/engine/install/ubuntu/).
2. Add the following line: ```127.0.0.1 server.listdev``` to the file: 

   > \etc\hosts
   
3. Clone this repository.
4. In the root directory of the project, call the `docker-compose up -d` command.
5. Call `docker-compose exec list-server bash` command and then run a file ***initDependencies*** with the command `sudo ./initDependencies`.
7. Run `bin/console update_database` command.
8. Create a new teacher to be able to log into the system with the `bin/console new_teacher` command.
9. The server should now be running at [http://server.listdev/admin](http://server.listdev/admin), you can now log into it using the credentials entered when creating the new teacher in the previous step.
   

## Application documentations:

* [MOSS Comparator setup](./docs/moss.md)
