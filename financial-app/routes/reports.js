const express = require('express');
const router = express.Router();
const { 
  generateFinancialReport,
  getUserActivityReport
} = require('../controllers/reportController');

router.get('/financial', generateFinancialReport);
router.get('/user-activity', getUserActivityReport);

module.exports = router;