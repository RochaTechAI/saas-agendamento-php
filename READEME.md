# 🏥 MedSaaS - Sistema de Agendamento Médico Multi-tenant

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)

> 🚧 **Status:** Em desenvolvimento (Fase 2: Arquitetura de Banco de Dados e Modelagem MVC)

## 📖 Sobre o Projeto
O **MedSaaS** é um Software as a Service (SaaS) construído do zero, focado na gestão de agendamentos para clínicas médicas. O grande diferencial deste projeto é a sua estrutura **Multi-tenant**, ou seja, ele é desenhado na camada de banco de dados para que múltiplas clínicas utilizem o mesmo sistema de forma totalmente isolada e segura.

Este projeto foi criado com foco estrito em **Engenharia de Software**, priorizando código limpo, separação de responsabilidades (MVC) e facilidade de configuração em qualquer ambiente através de containers.

## ✨ O que já foi implementado
- [x] **Infraestrutura com Docker:** Servidores web (Apache/PHP 8.2) e banco de dados (MySQL 8.0) 100% conteinerizados.
- [x] **Design Pattern Singleton:** Conexão com o banco de dados otimizada para usar apenas uma instância na memória usando `PDO` (prevenindo SQL Injection).
- [x] **Migrations Automatizadas:** Script PHP para criação automática das tabelas relacionais (`clinicas`, `medicos`, `grade_horarios`, `agendamentos`) e injeção de dados de teste.
- [ ] **Lógica de Agendamento:** Algoritmo de cruzamento de horários disponíveis vs. ocupados. *(Em andamento)*

## 🚀 Como executar este projeto na sua máquina

Como o projeto utiliza Docker, você não precisa instalar o PHP ou o MySQL no seu computador. Basta ter o Docker e o Git instalados.

1. Clone este repositório:
```bash
git clone https://github.com/RochaTechAI/saas-agendamento-php.git