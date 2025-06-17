# hibrido
Processo Seletivo - Teste #1 - Meta Tag em Multi-site

Extrair o módulo dentro da raiz do projeto. Ex:app/code/Hibrido/Metatag 
Depois disso, basta rodar os comandos de instalação:

bin/magento setup:upgrade
bin/magento cache:flush

# Instruções:

Insira um bloco no <head> de páginas CMS;

Detecte a URL da página CMS atual;

Verifique em quais store views essa página está ativa;

Adicione no head as tags <link rel="alternate" hreflang="..." href="..."> com base na configuração de idioma de cada store.
