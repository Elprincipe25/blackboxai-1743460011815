const express = require('express');
const router = express.Router();
const { 
  createTransaction, 
  getTransactions, 
  verifyTransaction 
} = require('../controllers/transactionController');

router.post('/', createTransaction);
router.get('/', getTransactions);
router.patch('/:id/verify', verifyTransaction);

module.exports = router;