# Contribution

To contribute to this project, follow the steps below.

**N.B. Before following these steps, you must install [git](https://git-scm.com/) and [composer](https://getcomposer.org/) on your local machine and create a Github account.**

------------------------------------------------------------------------------------------------------------------------------------------

## Procedure for making changes to the project

### 1) Creation of a local fork of the project

Click on the "[fork](https://docs.github.com/en/github/getting-started-with-github/fork-a-repo)" button at the top right of the page. This will create a copy of this repository in your own Github account with the name : "**forked from Scratchy-Show/TodoList**".

### 2) Create a local copy

Clone your copy from GitHub to your local machine :
```
git clone https://github.com/YOUR-GITHUB-USERNAME/TodoList.git
```

### 3) Install the project and its dependencies

Install the project by referring to [README](https://github.com/Scratchy-Show/TodoList/blob/master/README.md)

### 4) Create a new branch

Navigate to the repository directory on your computer.  
Create the new branch using a logical name corresponding to the changes or new features :
```
git checkout -b new-feature
```

### 5) Add new tests related to modifications

To implement new tests, refer to the official Symfony documentation.  
Run the tests with generation of a code coverage report to ensure that all the new code is running :
```
php bin / phpunit --coverage-html tests / Coverage
```

### 6) Validate the modifications

Commit your changes.  
Clearly detail the changes made.
```
git add.
git commit -m 'commit message'
```

Submit the changes to your forke repository
```
git push origin new-feature
```

### 7) Create a Pull Request

Go to your forke repository, you will see that your new branch is listed at the top with a handy "**Compare & pull request**" button. Click on this button.  
Be sure to provide a short title and explain why you created it, in the description box.

### 8) Submit the Pull Request

You must now submit the extract request to the original repository. To do this, press the "**Create pull request**" button and you are done.

### 9) Returns

If you are prompted to add or change anything, do not create a new checkout request. Make sure you are on the correct branch and make the new changes.
```
git checkout new-feature
```

------------------------------------------------------------------------------------------------------------------------------------------

## 2) Quality process to use

### Code review

For this project, we are using a free code reviewer that automates code reviews and monitors code quality over time: **[Codacy](https://www.codacy.com/)**  
The score must be at least B.

### Test

For this project, we are using **[PHPUnit](https://phpunit.de/)** for unit and functional tests and we have done some code coverage which you can find in the documentation **tests/Coverage directory**.

### Recommendations to follow :

Run PHPUnit regularly to verify the code.  
Implement your own tests, but make sure you don't decrease code coverage (100%).  
Make sure you don't modify existing tests.

### Performance

To check the performance impact of this project, we're using **[Blackfire](https://blackfire.io/)**, so use that too.

------------------------------------------------------------------------------------------------------------------------------------------

## 3) Rules to follow

Symfony defines coding standards that all contributions must follow :
 
[PSR-1](https://www.php-fig.org/psr/psr-1/)  
[PSR-2](https://www.php-fig.org/psr/psr-2/)  
[Symfony - Coding Standards](https://symfony.com/doc/current/contributing/code/standards.html)  
[Symfony - Conventions](https://symfony.com/doc/current/contributing/code/conventions.html)  
[Symfony - Framework Best Practices](https://symfony.com/doc/current/best_practices.html)  
[Twig - Coding Standards](https://twig.symfony.com/doc/2.x/coding_standards.html)

Please also follow these recommendations :  
Have a readable code, use understandable variable names, avoid overly complex code and write comments in French.

Thank you for your contribution to this project !
