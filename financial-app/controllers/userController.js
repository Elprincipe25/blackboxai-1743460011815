const { User } = require('../models');

const getAllUsers = async (req, res) => {
  try {
    const users = await User.findAll({
      attributes: ['id', 'username', 'role', 'businessType', 'isActive'],
      where: { isActive: true }
    });
    res.json(users);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const updateUser = async (req, res) => {
  try {
    const { id } = req.params;
    const { role, businessType, isActive } = req.body;

    const user = await User.findByPk(id);
    if (!user) {
      return res.status(404).json({ error: 'User not found' });
    }

    // Prevent self-demotion or deactivation
    if (id === req.user.id && (role !== user.role || isActive === false)) {
      return res.status(403).json({ error: 'Cannot modify your own role/status' });
    }

    if (role) user.role = role;
    if (businessType) user.businessType = businessType;
    if (isActive !== undefined) user.isActive = isActive;

    await user.save();
    res.json(user);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const deleteUser = async (req, res) => {
  try {
    const { id } = req.params;

    // Prevent self-deletion
    if (id === req.user.id) {
      return res.status(403).json({ error: 'Cannot delete your own account' });
    }

    const user = await User.findByPk(id);
    if (!user) {
      return res.status(404).json({ error: 'User not found' });
    }

    // Soft delete by marking as inactive
    user.isActive = false;
    await user.save();

    res.json({ message: 'User deactivated successfully' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = { getAllUsers, updateUser, deleteUser };