# 1. Clone o repositório
git clone https://github.com/MathBriton/Song-Clasifier.git
cd Song-Clasifier

# 2. Execute com Docker
docker-compose up -d

# 3. Aguarde a inicialização (30 segundos)
sleep 30

# 4. Execute as migrações e seeds
docker-compose exec laravel-app php artisan migrate:fresh --seed
docker-compose exec laravel-app php artisan jwt:secret

# 5. Acesse a aplicação
# Frontend: http://localhost:3000
# API: http://localhost:8000
# Admin: admin@tiaocarreiro.com.br / admin123

#####

# ª Requisitos Atendidos

1ª Backend e Frontend como API REST (PHP 8.1) ✅
2ª Backend em Laravel v11 ✅
3ª SPA com ReactJS ✅
4ª Testes para React e Laravel ✅
5ª Docker para padronizar ambiente ✅
6ª Modernizado com Tailwind CSS ✅
7ª Lista da 6ª música em diante com paginação ✅
8ª Camada de autenticação para aprovação/reprovação ✅
9ª CRUD completo para usuário autenticado ✅
10ª Documentação completa no README ✅

# ª Funcionalidades Extras

1ª Clean Architecture no backend ✅
2ª TypeScript no frontend ✅
3ª Zustand para gerenciamento de estado ✅
4ª JWT Authentication com refresh tokens ✅
5ª PostgreSQL como banco principal ✅
6ª Redis para cache e sessions ✅
7ª Testes automatizados com cobertura ✅
8ª API Documentation com Swagger ✅
9ª Docker Compose completo ✅
10ª CI/CD ready com scripts de teste ✅

###

# Atenção : Backend(Laravel).

# Executar migrations
docker-compose exec laravel-app php artisan migrate

# Executar seeders
docker-compose exec laravel-app php artisan db:seed

# Executar testes
docker-compose exec laravel-app php artisan test

# Limpar cache
docker-compose exec laravel-app php artisan cache:clear

### Atenção : Frontend(React)

# Instalar dependências
docker-compose exec react-app npm install

# Executar testes
docker-compose exec react-app npm test

# Build para produção
docker-compose exec react-app npm run build

# Atenção : Testes

# Executar todos os testes
./run-tests.sh