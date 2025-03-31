const { Transaction } = require('../models');

const createTransaction = async (req, res) => {
  try {
    const { amount, type, description, businessType } = req.body;
    const userId = req.user.id;

    const transaction = await Transaction.create({
      amount,
      type,
      description,
      businessType,
      userId
    });

    res.status(201).json(transaction);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getTransactions = async (req, res) => {
  try {
    const { businessType, startDate, endDate } = req.query;
    const where = { userId: req.user.id };

    if (businessType) where.businessType = businessType;
    if (startDate && endDate) {
      where.date = {
        [Op.between]: [new Date(startDate), new Date(endDate)]
      };
    }

    const transactions = await Transaction.findAll({ where });
    res.json(transactions);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const verifyTransaction = async (req, res) => {
  try {
    const { id } = req.params;
    const transaction = await Transaction.findByPk(id);

    if (!transaction) {
      return res.status(404).json({ error: 'Transaction not found' });
    }

    // Only accountants and director can verify transactions
    if (req.user.role === 'manager') {
      return res.status(403).json({ error: 'Insufficient permissions' });
    }

    transaction.isVerified = true;
    await transaction.save();

    res.json(transaction);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = { createTransaction, getTransactions, verifyTransaction };