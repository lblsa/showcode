//-----------------------------------------------------------------------------------
// FILENAME: PinChange.cs
// Copyright � 2011 Complex Systems. All rights reserved.
// ----------------------------------------------------------------------------------
// DESCRIPTION: ���� ����� ��������� ���-���� ��� ������� � ���������� ����������. 
// ----------------------------------------------------------------------------------
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.IO;

namespace SQScanner
{
    /// <summary>
    /// ����� ����� ��������� ���-����.
    /// </summary>
    public partial class PinChange : Form
    {
        Settings _settingsForm;

        private const string pathToPinFile = "\\Application\\ShowCode\\pin.txt";

        /// <summary>
        /// ����������� ����������� �����.
        /// </summary>
        public PinChange()
        {
            InitializeComponent();
        }

        /// <summary>
        /// ������������� ������������ �����.
        /// </summary>
        /// <param name="f1">���������� ��� ����� � ������ ��������. ����� ��� �������� ������� �����.</param>
        public PinChange(Settings f1)
        {
            InitializeComponent();
            _settingsForm = f1;
        }

        /// <summary>
        /// ����� ��������� �������� ������� �������� ��� ��������� �������� �������.
        /// </summary>
        private void checkBox1_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                this.checkBox2.Checked = false;
            else
                this.checkBox2.Checked = true;
        }

        /// <summary>
        /// ����� ��������� �������� ������� �������� ��� ��������� �������� �������.
        /// </summary>
        private void checkBox2_Click(object sender, EventArgs e)
        {
            if (this.checkBox2.Checked == true)
                this.checkBox1.Checked = false;
            else
                this.checkBox1.Checked = true;
        }

        /// <summary>
        /// ����� ������������ ������� ������ "������" ��� �������� � ����� �������� ����������.
        /// </summary>
        private void cancelButton_Click_1(object sender, EventArgs e)
        {
            _settingsForm.Show();
            this.Close();
        }

        /// <summary>
        /// ����� ������� ���������� ���-����. ������� ��� ���� �����.
        /// </summary>
        private void clearButton_Click(object sender, EventArgs e)
        {
            passwordTextBox1.Text = "";
            passwordTextBox2.Text = "";
        }

        // ����� ���� ������ ������� ����� ���� ���-���� � ���� �����.
        private void button1_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "1";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "1";
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "2";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "2";
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "3";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "3";
        }

        private void button4_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "4";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "4";
        }

        private void button5_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "5";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "5";
        }

        private void button6_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "6";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "6";
        }

        private void button7_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "7";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "7";
        }

        private void button8_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "8";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "8";
        }

        private void button9_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "9";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "9";
        }

        private void button_0_Click(object sender, EventArgs e)
        {
            if (this.checkBox1.Checked == true)
                passwordTextBox1.Text = passwordTextBox1.Text + "0";
            else
                passwordTextBox2.Text = passwordTextBox2.Text + "0";
        }

        /// <summary>
        /// ����� ������������ ������� ������ "����" ��� ���������� ������ ���-����.
        /// </summary>
        private void enterButton_Click(object sender, EventArgs e)
        {
            // ��������� �� ��������� ��� ��������� ���-����.
            if (passwordTextBox1.Text == passwordTextBox2.Text)
            {
                saveNewPin();
                this.Close();
                _settingsForm.Show();
            }
            else
            {
                // ���� ���-���� �� �������, ������� ���� ����� � ������ ��������������� ��������� ������������.
                passwordTextBox1.Text = "";
                passwordTextBox2.Text = "";
                MessageBox.Show("���-��� �� ������ ���� �� ��������� � ���-����� � ������ ����!");
            }
        }

        /// <summary>
        /// ����� ���������� ������ ���-���� � ��������� �����.
        /// </summary>
        private void saveNewPin()
        {
            using (StreamWriter sw = new StreamWriter(pathToPinFile))
            {
                sw.Write(this.passwordTextBox1.Text);
            }
        }
    }
}