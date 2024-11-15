git init
git config --local user.name "project"
git config --local user.email "project@ur.edu.pl"
git add --all
git commit -m "project petShelter"
git archive --format=zip HEAD -o ../petShelter_archive.zip
pause
