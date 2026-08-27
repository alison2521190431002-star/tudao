echo "# tudao %date% %time%" > README.md
git init
git add . -v 
git commit -m "first commit"
git branch -M main
git remote add origin git@github.com:alison2521190431002-star/tudao.git
git push -u origin main