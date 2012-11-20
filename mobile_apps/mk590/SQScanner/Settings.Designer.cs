namespace SQScanner
{
    partial class Settings
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;
        private System.Windows.Forms.MainMenu mainMenu1;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.mainMenu1 = new System.Windows.Forms.MainMenu();
            this.setEventButton = new System.Windows.Forms.Button();
            this.exitButton = new System.Windows.Forms.Button();
            this.cancelButton = new System.Windows.Forms.Button();
            this.buttonPinChange = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // setEventButton
            // 
            this.setEventButton.Font = new System.Drawing.Font("Tahoma", 12F, System.Drawing.FontStyle.Bold);
            this.setEventButton.Location = new System.Drawing.Point(30, 13);
            this.setEventButton.Name = "setEventButton";
            this.setEventButton.Size = new System.Drawing.Size(260, 40);
            this.setEventButton.TabIndex = 0;
            this.setEventButton.Text = "Задать мероприятие";
            this.setEventButton.Click += new System.EventHandler(this.setEventButton_Click);
            // 
            // exitButton
            // 
            this.exitButton.Font = new System.Drawing.Font("Tahoma", 12F, System.Drawing.FontStyle.Bold);
            this.exitButton.Location = new System.Drawing.Point(30, 104);
            this.exitButton.Name = "exitButton";
            this.exitButton.Size = new System.Drawing.Size(260, 40);
            this.exitButton.TabIndex = 1;
            this.exitButton.Text = "Выход из приложения";
            this.exitButton.Click += new System.EventHandler(this.exitButton_Click);
            // 
            // cancelButton
            // 
            this.cancelButton.Font = new System.Drawing.Font("Tahoma", 12F, System.Drawing.FontStyle.Bold);
            this.cancelButton.Location = new System.Drawing.Point(30, 150);
            this.cancelButton.Name = "cancelButton";
            this.cancelButton.Size = new System.Drawing.Size(260, 40);
            this.cancelButton.TabIndex = 2;
            this.cancelButton.Text = "Возврат к сканированию";
            this.cancelButton.Click += new System.EventHandler(this.cancelButton_Click);
            // 
            // buttonPinChange
            // 
            this.buttonPinChange.Font = new System.Drawing.Font("Tahoma", 12F, System.Drawing.FontStyle.Bold);
            this.buttonPinChange.Location = new System.Drawing.Point(30, 59);
            this.buttonPinChange.Name = "buttonPinChange";
            this.buttonPinChange.Size = new System.Drawing.Size(260, 40);
            this.buttonPinChange.TabIndex = 3;
            this.buttonPinChange.Text = "Изменить пин-код";
            this.buttonPinChange.Click += new System.EventHandler(this.buttonPinChange_Click);
            // 
            // Settings
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(96F, 96F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Dpi;
            this.AutoScroll = true;
            this.BackColor = System.Drawing.SystemColors.ControlLightLight;
            this.ClientSize = new System.Drawing.Size(318, 215);
            this.ControlBox = false;
            this.Controls.Add(this.buttonPinChange);
            this.Controls.Add(this.cancelButton);
            this.Controls.Add(this.exitButton);
            this.Controls.Add(this.setEventButton);
            this.Name = "Settings";
            this.Text = "Настройки приложения";
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Button setEventButton;
        private System.Windows.Forms.Button exitButton;
        private System.Windows.Forms.Button cancelButton;
        private System.Windows.Forms.Button buttonPinChange;
    }
}