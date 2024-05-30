pipeline {
    agent any

    stages {
        stage('git') {
            steps {
                checkout scmGit(branches: [[name: '*/main']], extensions: [], userRemoteConfigs: [[url: 'https://github.com/regal2t/CI_CD_PIPELINE_1.git']])
            }
        }
        stage('build') {
            steps {
                sh 'sudo docker-compose up -d'
            }
        }
    }
}