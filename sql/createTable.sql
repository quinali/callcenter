CREATE TABLE `survey_operators` (
  `idSurvey` int(11) DEFAULT NULL,
  `idOperator` varchar(20) DEFAULT NULL,
  `nameOperator` varchar(100) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  KEY `pk` (`idSurvey`,`idOperator`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

