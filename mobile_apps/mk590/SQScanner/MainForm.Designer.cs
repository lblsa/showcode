namespace SQScanner
{
    partial class MainForm
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
            this.labelInfo = new System.Windows.Forms.Label();
            this.buttonSettings = new System.Windows.Forms.Button();
            this.labelMain = new System.Windows.Forms.Label();
            this.labelEvent = new System.Windows.Forms.Label();
            this.SuspendLayout();
            // 
            // labelInfo
            // 
            this.labelInfo.Font = new System.Drawing.Font("Tahoma", 10F, System.Drawing.FontStyle.Bold);
            this.labelInfo.Location = new System.Drawing.Point(0, 46);
            this.labelInfo.Name = "labelInfo";
            this.labelInfo.Size = new System.Drawing.Size(318, 20);
            this.labelInfo.Text = "Поднесите билет к сканеру";
            this.labelInfo.TextAlign = System.Drawing.ContentAlignment.TopCenter;
            // 
            // buttonSettings
            // 
            this.buttonSettings.BackColor = System.Drawing.SystemColors.ActiveCaptionText;
            this.buttonSettings.Location = new System.Drawing.Point(91, 186);
            this.buttonSettings.Name = "buttonSettings";
            this.buttonSettings.Size = new System.Drawing.Size(145, 29);
            this.buttonSettings.TabIndex = 1;
            this.buttonSettings.Text = "Настройка";
            this.buttonSettings.Click += new System.EventHandler(this.buttonSettings_Click);
            // 
            // labelMain
            // 
            this.labelMain.Font = new System.Drawing.Font("Tahoma", 22F, System.Drawing.FontStyle.Bold);
            this.labelMain.Location = new System.Drawing.Point(0, 88);
            this.labelMain.Name = "labelMain";
            this.labelMain.Size = new System.Drawing.Size(318, 56);
            this.labelMain.TextAlign = System.Drawing.ContentAlignment.TopCenter;
            // 
            // labelEvent
            // 
            this.labelEvent.Font = new System.Drawing.Font("Tahoma", 10F, System.Drawing.FontStyle.Bold);
            this.labelEvent.Location = new System.Drawing.Point(0, 15);
            this.labelEvent.Name = "labelEvent";
            this.labelEvent.Size = new System.Drawing.Size(318, 20);
            this.labelEvent.TextAlign = System.Drawing.ContentAlignment.TopCenter;
            // 
            // MainForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(96F, 96F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Dpi;
            this.AutoScroll = true;
            this.BackColor = System.Drawing.SystemColors.HighlightText;
            this.ClientSize = new System.Drawing.Size(318, 215);
            this.Controls.Add(this.labelEvent);
            this.Controls.Add(this.labelMain);
            this.Controls.Add(this.buttonSettings);
            this.Controls.Add(this.labelInfo);
            this.MaximizeBox = false;
            this.MinimizeBox = false;
            this.Name = "MainForm";
            this.Text = "ShowCode";
            this.TopMost = true;
            this.Load += new System.EventHandler(this.Form1_Load);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Label labelInfo;
        private System.Windows.Forms.Button buttonSettings;
        private System.Windows.Forms.Label labelMain;
        private System.Windows.Forms.Label labelEvent;
    }
}

