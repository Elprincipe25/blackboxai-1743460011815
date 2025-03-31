const { Transaction, User } = require('../models');
const { Op } = require('sequelize');

const generateFinancialReport = async (req, res) => {
  try {
    const { businessType, startDate, endDate } = req.query;
    const where = { isVerified: true };

    if (businessType) where.businessType = businessType;
    if (startDate && endDate) {
      where.date = {
        [Op.between]: [new Date(startDate), new Date(endDate)]
      };
    }

    // For managers, only show their own business data
    if (req.user.role === 'manager') {
      where.userId = req.user.id;
    }

    const transactions = await Transaction.findAll({ where });
    
    const report = {
      totalIncome: transactions
        .filter(t => t.type === 'income')
        .reduce((sum, t) => sum + parseFloat(t.amount), 0),
      totalExpenses: transactions
        .filter(t => t.type === 'expense')
        .reduce((sum, t) => sum + parseFloat(t.amount), 0),
      transactionCount: transactions.length,
      businessType: businessType || 'all',
      period: startDate && endDate ? `${startDate} to ${endDate}` : 'all time'
    };

    report.netProfit = report.totalIncome - report.totalExpenses;

    res.json(report);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getUserActivityReport = async (req, res) => {
  try {
    // Only director can access this report
    if (req.user.role !== 'director') {
      return res.status(403).json({ error: 'Insufficient permissions' });
    }

    const users = await User.findAll({
      include: [{
        model: Transaction,
        attributes: ['id'],
        where: { isVerified: true }
      }],
      attributes: ['id', 'username', 'role', 'businessType']
    });

    const report = users.map(user => ({
      userId: user.id,
      username: user.username,
      role: user.role,
      businessType: user.businessType,
      transactionCount: user.Transactions.length
    }));

    res.json(report);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = { generateFinancialReport, getUserActivityReport };