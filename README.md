# Aplicativo-base-para-o-projeto-de-deploy-em-cluster-utilizando-o-Kubernates
Este projeto demonstra uma aplicação web simples composta por um formulário de contato (frontend em HTML/CSS/JavaScript) e um backend em PHP que persiste os dados em um banco de dados MySQL. A aplicação é empacotada em containers Docker e implantada em um cluster Kubernetes local usando Minikube.

Descrição da Aplicação
A aplicação consiste em:

Frontend: Um formulário HTML estilizado com CSS e lógica JavaScript (jQuery) para capturar o nome, email e comentário do usuário.

Backend: Um script PHP que recebe os dados do formulário via requisição AJAX e os insere em uma tabela no banco de dados MySQL.

Banco de Dados: Um servidor MySQL que armazena as mensagens enviadas pelo formulário.

Pré-requisitos
Antes de iniciar, certifique-se de ter os seguintes softwares instalados em sua máquina:

Docker Desktop (inclui o Docker Engine)

Minikube

kubectl

Configuração e Execução
Siga os passos abaixo para configurar e rodar a aplicação em seu ambiente local com Minikube.

1. Iniciar o Minikube e Configurar o Ambiente Docker
Abra seu terminal (CMD ou PowerShell no Windows, ou terminal no Linux/macOS) e execute:

Bash

minikube start
Para que o Docker em seu terminal construa imagens diretamente no ambiente Minikube, execute o comando apropriado para o seu shell:

No Windows (CMD):

Snippet de código

FOR /F "tokens=*" %i IN ('minikube -p minikube docker-env --shell cmd') DO %i
No Windows (PowerShell):

PowerShell

minikube -p minikube docker-env | Invoke-Expression
No Linux/macOS (Bash/Zsh):

Bash

eval $(minikube docker-env)
2. Construir as Imagens Docker
Navegue até o diretório raiz do seu projeto (projeto1-k8s/) e construa as imagens Docker:

Para o Backend (PHP/Apache):

Bash

docker build -t projeto1-app-backend:1.0 backend/
Para o Banco de Dados (MySQL - se houver um Dockerfile customizado):

Bash

docker build -t projeto1-app-database:1.0 database/
(Nota: Para o MySQL, muitas vezes é suficiente usar a imagem oficial diretamente no Kubernetes, sem a necessidade de construir uma imagem customizada, a menos que você tenha configurações ou scripts de inicialização específicos dentro de um Dockerfile no diretório database/.)

3. Aplicar os Manifestos do Kubernetes
Agora, aplique os arquivos de configuração do Kubernetes na ordem correta. Certifique-se de estar no diretório raiz do seu projeto.

a. Criar o Secret para a senha do MySQL:
Primeiro, a senha "Senha123" codificada em Base64 é U2VuaGExMjM=. O mysql-secret.yml já contém isso.

Bash

kubectl apply -f mysql-secret.yml
b. Criar o PersistentVolumeClaim para o MySQL (para persistência de dados):

Bash

kubectl apply -f persistent-volume-claim.yml
c. Criar o Deployment para o MySQL:

Bash

kubectl apply -f deployment-mysql.yml
d. Criar o Service para o MySQL (para comunicação interna):

Bash

kubectl apply -f service-mysql.yml
e. Criar o Deployment para a Aplicação PHP:

Bash

kubectl apply -f deployment-app.yml
f. Criar o Service para a Aplicação PHP (para acesso externo):

Bash

kubectl apply -f service-app.yml
4. Inicializar o Banco de Dados
Após o pod do MySQL estar rodando, você precisa criar a tabela mensagens.

a. Verifique o nome do pod do MySQL:

Bash

kubectl get pods -l app=mysql
Anote o nome completo do pod (ex: mysql-deployment-xxxxxxxxxx-yyyyy).

b. Copie o script SQL para o pod do MySQL:

Bash

kubectl cp database/mensagens.sql <nome-do-pod-mysql>:/tmp/mensagens.sql
(Substitua <nome-do-pod-mysql> pelo nome que você obteve no passo anterior).

c. Execute o script SQL dentro do container do MySQL:

Bash

kubectl exec -it <nome-do-pod-mysql> -- mysql -u root -pSenha123 meubanco -e "source /tmp/mensagens.sql"
5. Verificar o Status da Implantação
Você pode verificar se todos os seus pods e serviços estão rodando corretamente com os seguintes comandos:

Bash

kubectl get pods
kubectl get svc
Certifique-se de que todos os pods estejam no status Running.

6. Acessar a Aplicação
Para acessar a aplicação web em seu navegador, use o comando minikube service:

Bash

minikube service projeto1-service
Este comando abrirá automaticamente seu navegador web padrão, exibindo o formulário de contato. Preencha o formulário e clique em "Enviar" para testar a funcionalidade de gravação no banco de dados.

Limpeza (Opcional)
Para remover todos os recursos do Kubernetes criados por este projeto e parar o Minikube:

Bash

kubectl delete -f .  # Isso exclui todos os manifestos .yml no diretório atual
minikube stop
minikube delete
